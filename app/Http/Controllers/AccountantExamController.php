<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ExamSchedule;
use App\Models\ExamTerm;

class AccountantExamController extends Controller
{

    public function index()
    {
        $activeTerm = ExamTerm::where('is_active', true)->first();
        $terms = ExamTerm::orderBy('start_date', 'desc')->get();
        $classes = \App\Models\SchoolClass::all();

        return view('accountant.exams.index', compact('activeTerm', 'terms', 'classes'));
    }

    public function showClass(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'term_id' => 'required|exists:exam_terms,id',
        ]);

        $classId = $request->class_id;
        $termId = $request->term_id;
        $activeTerm = ExamTerm::findOrFail($termId);
        $class = \App\Models\SchoolClass::findOrFail($classId);

        // Fetch students in the class with parents with pagination to prevent memory exhaustion
        $students = Student::where('class_id', $classId)->with('parent')->paginate(50);

        // Optimize Balance Calculation for the current page of students
        $studentIds = $students->pluck('id')->toArray();
        $feesMap = \App\Models\StudentFee::whereIn('student_id', $studentIds)
            ->where('status', '!=', 'paid')
            ->selectRaw('student_id, SUM(amount + late_fee - discount) as total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id');

        $paymentsMap = \App\Models\FeePayment::whereIn('student_fee_id', function ($q) use ($studentIds) {
            $q->select('id')->from('student_fees')->whereIn('student_id', $studentIds);
        })
            ->selectRaw('student_fees.student_id, SUM(fee_payments.amount_paid) as total')
            ->join('student_fees', 'fee_payments.student_fee_id', '=', 'student_fees.id')
            ->groupBy('student_fees.student_id')
            ->pluck('total', 'student_id');

        // Attach balances
        foreach ($students as $student) {
            $totalFees = $feesMap[$student->id] ?? 0;
            $totalPaid = $paymentsMap[$student->id] ?? 0;
            $student->pending_balance = max(0, $totalFees - $totalPaid);
        }

        return view('accountant.exams.class_list', compact('students', 'activeTerm', 'class'));
    }

    public function printBatch(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'term_id' => 'required|exists:exam_terms,id',
            'type' => 'required|in:all,paid,unpaid',
        ]);

        $classId = $request->class_id;
        $termId = $request->term_id;
        $type = $request->type;

        $activeTerm = ExamTerm::findOrFail($termId);
        $class = \App\Models\SchoolClass::findOrFail($classId);

        // Fetch Schedules
        $schedules = ExamSchedule::where('class_id', $classId)
            ->where('term_id', $termId)
            ->with('subject')
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        if ($schedules->isEmpty()) {
            return redirect()->back()->with('error', 'No exam schedule found for this class and term.');
        }

        // Fetch Students with parents - Cap at 100 to prevent memory exhaustion
        $students = Student::where('class_id', $classId)->with('parent')->take(100)->get();

        // Optimize Balance Calculation: Fetch all unpaid fee totals and all payments for the class in one go
        $studentIds = $students->pluck('id')->toArray();
        $feesMap = \App\Models\StudentFee::whereIn('student_id', $studentIds)
            ->where('status', '!=', 'paid')
            ->selectRaw('student_id, SUM(amount + late_fee - discount) as total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id');

        $paymentsMap = \App\Models\FeePayment::whereIn('student_fee_id', function ($q) use ($studentIds) {
            $q->select('id')->from('student_fees')->whereIn('student_id', $studentIds);
        })
            ->selectRaw('student_fees.student_id, SUM(fee_payments.amount_paid) as total')
            ->join('student_fees', 'fee_payments.student_fee_id', '=', 'student_fees.id')
            ->groupBy('student_fees.student_id')
            ->pluck('total', 'student_id');

        // Filter and Prepare
        $filteredStudents = [];
        foreach ($students as $student) {
            $totalFees = $feesMap[$student->id] ?? 0;
            $totalPaid = $paymentsMap[$student->id] ?? 0;
            $balance = max(0, $totalFees - $totalPaid);

            if ($type === 'paid' && $balance > 0) continue;
            if ($type === 'unpaid' && $balance <= 0) continue;

            // Attach extra data for the view
            $student->pending_balance = $balance;
            $filteredStudents[] = $student;
        }

        return view('accountant.exams.print_batch', compact('filteredStudents', 'activeTerm', 'class', 'schedules'));
    }

    public function viewSlip($studentId)
    {
        $student = Student::findOrFail($studentId);

        // Bypass Fee Check for Admin/Accountant

        // Fetch Active Term
        $activeTerm = ExamTerm::where('is_active', true)->first();

        if (!$activeTerm) {
            return redirect()->back()->with('error', 'No active exam term found.');
        }

        // Fetch Exam Schedule
        $schedules = ExamSchedule::where('class_id', $student->class_id)
            ->where('term_id', $activeTerm->id)
            ->with('subject')
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        // Optional: Pass a flag to view to indicate "Admin Override Mode" if needed
        return view('student.exams.admit_card', compact('student', 'activeTerm', 'schedules'));
    }
}

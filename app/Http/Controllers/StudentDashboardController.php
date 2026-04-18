<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login')->with('error', 'Please login as a student.');
        }

        /** @var \App\Models\Student $student */
        $student = Auth::guard('student')->user();
        $studentId = $student->id;

        // Eager load class for the already authenticated student if needed, or rely on lazy loading/fresh query if specific relations are key.
        // Since we already have the model, we can just load the relationship.
        $student->load('schoolClass');

        // Count active assignments (due date > now) for the student's class
        $assignmentsDueCount = Assignment::where('school_class_id', $student->class_id)
            ->where('due_date', '>', now())
            ->count();

        // Fetch upcoming assignments (limit 3, ordered by due date)
        $upcomingAssignments = Assignment::where('school_class_id', $student->class_id)
            ->where('due_date', '>', now())
            ->orderBy('due_date', 'asc')
            ->take(3)
            ->with('subject')
            ->get();

        // Count enrolled courses (subjects for the student's class)
        $enrolledCoursesCount = Subject::count(); // Count all subjects since subjects table doesn't have class relationship

        // Calculate attendance rate
        $totalAttendance = Attendance::where('student_id', $studentId)->count();
        $presentCount = Attendance::where('student_id', $studentId)
            ->where('status', 'present')
            ->count();

        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 0;

        // Calculate Average Percentage from Exam Results (Obtained / Total)
        $examResults = \App\Models\ExamResult::where('student_id', $studentId)->get();
        $totalObtained = $examResults->sum('obtained_marks');
        $totalMaxMarks = $examResults->sum('total_marks');
        $averagePercentage = $totalMaxMarks > 0 ? round(($totalObtained / $totalMaxMarks) * 100, 1) : 0;

        // Calculate "Fees Due" (Total Outstanding Amount)
        // Logic: Sum of (amount + late_fee - discount) - paid_amount? 
        // Or simpler: Sum of total_amount for unpaid invoices if partial payments aren't tracked deeply here.
        // Assuming 'status' != 'paid'.
        // Better: Fetch all non-paid fees and sum their 'total_amount' via logic or database.
        // DB-side calculation for speed:
        $feesDue = \App\Models\StudentFee::where('student_id', $studentId)
            ->whereIn('status', ['unpaid', 'partial'])
            ->with('payments')
            ->get()
            ->sum(function ($fee) {
                $invoiceTotal = $fee->amount + $fee->late_fee - $fee->discount;
                $paid = $fee->payments->sum('amount_paid');
                return max(0, $invoiceTotal - $paid);
            });

        // Yearly Paid (Total Paid this Year)
        $yearlyFund = \App\Models\FeePayment::whereHas('studentFee', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })
            ->whereYear('payment_date', now()->year)
            ->sum('amount_paid');

        // Upcoming Events
        $upcomingEvents = \App\Models\Event::whereJsonContains('target_audience', 'student')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        // Upcoming Online Classes
        $onlineClasses = \App\Models\OnlineClass::where('school_class_id', $student->class_id)
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->with(['teacher', 'subject'])
            ->get();

        // Transport Details
        $transport = \App\Models\StudentTransport::where('student_id', $studentId)
            ->where('status', 'active')
            ->with('route')
            ->first();

        return view('dashboard', compact(
            'assignmentsDueCount',
            'upcomingAssignments',
            'enrolledCoursesCount',
            'attendanceRate',
            'averagePercentage',
            'yearlyFund',
            'feesDue',
            'upcomingEvents',
            'onlineClasses',
            'transport'
        ));
    }

    public function certificates()
    {
        $studentId = Auth::guard('student')->id();
        $certificates = \App\Models\Certificate::where('student_id', $studentId)
            ->where('status', 'issued')
            ->with('template')
            ->latest()
            ->paginate(10);

        return view('student.certificates.index', compact('certificates'));
    }

    public function viewCertificate($id)
    {
        $studentId = Auth::guard('student')->id();
        $certificate = \App\Models\Certificate::where('student_id', $studentId)
            ->where('id', $id)
            ->where('status', 'issued')
            ->with(['student', 'template'])
            ->firstOrFail();

        // Regenerate content
        $content = $this->generateCertificateContent($certificate->template->body, $certificate->student, $certificate);

        return view('admin.certificates.print', compact('certificate', 'content'));
    }

    private function generateCertificateContent($templateBody, $student, $certificate)
    {
        // Duplicate logic from CertificateController (or extract to Helper/Service later)
        $replacements = [
            '{{student_name}}' => $student->name,
            '{{father_name}}' => $student->father_name ?? $student->parent->father_name ?? 'N/A',
            '{{roll_no}}' => $student->roll_no ?? 'N/A',
            '{{class}}' => $student->schoolClass->name ?? 'N/A',
            '{{dob}}' => $student->custom1 ?? 'N/A',
            '{{admission_date}}' => $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M Y') : 'N/A',
            '{{issue_date}}' => $certificate->issue_date->format('d M Y'),
            '{{principal_name}}' => 'Principal', // Hardcoded or fetch from settings
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $templateBody);
    }
}

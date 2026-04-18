<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\ExamTerm;
use App\Models\Student;
use App\Models\ExamResult;

class AccountantResultController extends Controller
{
    public function index()
    {
        $activeTerm = ExamTerm::where('is_active', true)->first();
        $terms = ExamTerm::orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::all();

        return view('accountant.results.index', compact('activeTerm', 'terms', 'classes'));
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
        $class = SchoolClass::findOrFail($classId);

        // Fetch students in this class with parents with pagination to prevent memory exhaustion
        $students = Student::where('class_id', $classId)->with('parent')->paginate(50);

        return view('accountant.results.class_list', compact('students', 'activeTerm', 'class'));
    }

    public function printDmc($studentId, $termId)
    {
        $student = Student::with(['schoolClass', 'school'])->findOrFail($studentId);
        $term = ExamTerm::findOrFail($termId);

        // Fetch results with subject names
        $results = ExamResult::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->with('subject')
            ->get();

        // Calculate totals
        $totalObtained = $results->sum('obtained_marks');
        $totalMax = $results->sum('total_marks');

        $percentage = ($totalMax > 0) ? ($totalObtained / $totalMax) * 100 : 0;

        // Overall Grade
        $overallGrade = ExamResult::calculateGrade($totalObtained, $totalMax);

        return view('exams.result_card', compact('student', 'term', 'results', 'totalObtained', 'totalMax', 'percentage', 'overallGrade'));
    }
}

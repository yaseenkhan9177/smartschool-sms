<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ExamSchedule;
use App\Models\ExamTerm;
use Illuminate\Support\Facades\Auth;

class StudentExamController extends Controller
{
    public function admitCard()
    {
        $student = null;

        // 1. Try resolving from Student Guard
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
        }
        // 2. Try resolving from Web Guard (Unified Auth)
        elseif (Auth::user()) {
            $student = Student::where('email', Auth::user()->email)->first();
        }

        if (!$student) {
            return redirect()->back()->with('error', 'Access Denied: Unable to resolve Student Profile for the currently logged-in user.');
        }

        // 1. Fee Check (Gatekeeper)
        $pendingBalance = $student->currentFeeBalance();

        if ($pendingBalance > 0) {
            return view('student.exams.fee_blocked', compact('pendingBalance'));
        }

        // 2. Fetch Active Term
        $activeTerm = ExamTerm::where('is_active', true)->first();

        if (!$activeTerm) {
            return redirect()->back()->with('error', 'No active exam term found.');
        }

        // 3. Check if Schedule is Published
        $isPublished = ExamSchedule::where('class_id', $student->class_id)
            ->where('term_id', $activeTerm->id)
            ->where('is_published', true)
            ->exists();

        if (!$isPublished) {
            return view('student.exams.not_published');
        }

        // 4. Fetch Exam Schedule for Student's Class & Active Term
        $schedules = ExamSchedule::where('class_id', $student->class_id)
            ->where('term_id', $activeTerm->id)
            ->where('is_published', true)
            ->with('subject')
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        return view('student.exams.admit_card', compact('student', 'activeTerm', 'schedules'));
    }

    public function results()
    {
        $student = null;

        // 1. Try resolving from Student Guard
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
        }
        // 2. Try resolving from Web Guard (Unified Auth)
        elseif (Auth::user()) {
            $student = Student::where('email', Auth::user()->email)->first();
        }

        if (!$student) {
            return redirect()->back()->with('error', 'Access Denied: Student profile not found.');
        }

        // Get terms where this student has results
        $terms = ExamTerm::whereHas('examResults', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })
            ->with(['examResults' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->orderBy('start_date', 'desc')
            ->get();

        return view('student.exams.results', compact('student', 'terms'));
    }
}

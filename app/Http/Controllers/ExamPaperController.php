<?php

namespace App\Http\Controllers;

use App\Models\ExamPaper;
use App\Models\ExamTerm;
use App\Models\SchoolClass;
use App\Models\Subject; // Or whatever subject model/string logic we finalized
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamPaperController extends Controller
{
    // Teacher: Show Upload Form
    public function create()
    {
        $activeTerms = ExamTerm::where('is_active', true)
            ->where('end_date', '>=', now()) // only future/current terms
            ->get();
        // Assuming Teacher is logged in via 'teacher' guard or we get user->teacher profile
        $teacher = Auth::guard('teacher')->user();

        // Fetch classes assigned to teacher. Using the relationship I saw in Teacher model previously.
        // Or all classes if no strict assignment yet.
        $classes = $teacher ? $teacher->schoolClasses : SchoolClass::all();
        $subjects = Subject::all(); // Fetch actual list of subjects

        return view('teacher.exams.create', compact('activeTerms', 'classes', 'subjects'));
    }

    // Teacher: Store Paper
    public function store(Request $request)
    {
        $request->validate([
            'term_id' => 'required|exists:exam_terms,id',
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'paper_file' => 'required|mimes:pdf|max:10240', // 10MB max
        ]);

        $file = $request->file('paper_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/exam_papers'), $filename);

        ExamPaper::create([
            'term_id' => $request->term_id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => Auth::guard('teacher')->id() ?? 1,
            'file_path' => $filename,
            'status' => 'submitted',
            'submitted_at' => now(),
            'school_id' => Auth::guard('teacher')->user()->school_id ?? 1,
        ]);

        return redirect()->route('teacher.exams.create')->with('success', 'Exam Paper submitted successfully.');
    }

    // Admin/Accountant: List Submitted Papers
    public function submittedPapers()
    {
        $papers = ExamPaper::with(['term', 'class', 'subject', 'teacher'])
            ->where('status', 'submitted')
            ->latest()
            ->get();

        $layout = request()->routeIs('accountant.*') ? 'layouts.accountant' : 'layouts.admin';

        return view('admin.exams.submitted', compact('papers', 'layout'));
    }
}

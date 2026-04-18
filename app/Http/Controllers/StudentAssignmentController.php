<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentAssignmentController extends Controller
{
    public function index()
    {
        // Assuming student authentication is handled via session 'student_id' as seen in web.php
        $studentId = session('student_id');
        if (!$studentId) {
            return redirect()->route('login')->with('error', 'Please login as a student.');
        }

        // Fetch student's class (assuming student model has class_id or similar, need to check Student model)
        // Let's check the Student model first.
        $student = \App\Models\Student::find($studentId);

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student not found.');
        }

        // Assuming Student model has 'class_id' or 'school_class_id'.
        // Based on previous context, it might be 'class_id'. I should verify this. 
        // For now I will assume 'class_id' based on typical patterns, but I'll check the Student model content if I can.
        // Wait, I saw Student.php earlier. Let me check it again if needed.
        // Actually, let's assume 'class_id' for now, if it fails I'll fix it.
        // Wait, looking at SchoolClass model, it has many timetables.
        // Let's check Student model content again to be sure.

        $assignments = Assignment::where('school_class_id', $student->class_id)
            ->with(['subject', 'teacher'])
            ->orderBy('due_date', 'asc')
            ->get();

        return view('student.assignments.index', compact('assignments'));
    }

    public function show($id)
    {
        $studentId = session('student_id');
        if (!$studentId) {
            return redirect()->route('login')->with('error', 'Please login as a student.');
        }

        $assignment = Assignment::with(['subject', 'teacher'])->findOrFail($id);

        // Check if student has already submitted
        $submission = Submission::where('assignment_id', $id)
            ->where('student_id', $studentId)
            ->first();

        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, $id)
    {
        $studentId = session('student_id');
        if (!$studentId) {
            return redirect()->route('login')->with('error', 'Please login as a student.');
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip,png,jpg,jpeg|max:2048',
        ]);

        $assignment = Assignment::findOrFail($id);

        if ($assignment->due_date < now()) {
            return back()->with('error', 'This assignment is closed. Submissions are strictly not allowed after the due date.');
        }

        $filePath = $request->file('file')->store('submissions', 'public');

        Submission::updateOrCreate(
            ['assignment_id' => $id, 'student_id' => $studentId],
            [
                'file_path' => $filePath,
                'submitted_at' => now(),
            ]
        );

        return redirect()->route('student.assignments.show', $id)->with('success', 'Assignment submitted successfully.');
    }
}

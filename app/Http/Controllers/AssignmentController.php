<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;

class AssignmentController extends Controller
{
    public function index()
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));

        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['schoolClass', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));

        // Fetch classes assigned to the teacher
        $classes = $teacher->schoolClasses;
        $subjects = Subject::all();

        return view('teacher.assignments.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip,png,jpg,jpeg|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        $assignment = Assignment::create([
            'title' => $request->title,
            'description' => $request->description,
            'teacher_id' => $teacher->id,
            'school_class_id' => $request->school_class_id,
            'subject_id' => $request->subject_id,
            'due_date' => $request->due_date,
            'file_path' => $filePath,
        ]);

        // Send Notification to Students
        $class = \App\Models\SchoolClass::with('students')->find($request->school_class_id);
        if ($class && $class->students->count() > 0) {
            \Illuminate\Support\Facades\Notification::send($class->students, new \App\Notifications\GeneralNotification(
                'New Assignment: ' . $request->title,
                'A new assignment has been posted for ' . $class->name . ' in ' . $assignment->subject->name . '.',
                $teacher->name . ' (Teacher)',
                'teacher',
                $teacher->id,
                'assignment' // type
            ));
        }

        ActivityLogger::log('assignment', 'Created assignment "' . $limitTitle = \Illuminate\Support\Str::limit($request->title, 30) . '" for ' . $class->name);

        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment created and notifications sent.');
    }

    public function show($id)
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));

        $assignment = Assignment::where('id', $id)
            ->where('teacher_id', $teacher->id)
            ->with(['submissions.student', 'schoolClass', 'subject'])
            ->firstOrFail();

        return view('teacher.assignments.show', compact('assignment'));
    }

    public function edit($id)
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));
        $assignment = Assignment::where('id', $id)->where('teacher_id', $teacher->id)->firstOrFail();

        $classes = $teacher->schoolClasses;
        $subjects = \App\Models\Subject::all();

        return view('teacher.assignments.edit', compact('assignment', 'classes', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));
        $assignment = Assignment::where('id', $id)->where('teacher_id', $teacher->id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip,png,jpg,jpeg|max:2048',
        ]);

        $filePath = $assignment->file_path;
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($assignment->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($assignment->file_path);
            }
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        $assignment->update([
            'title' => $request->title,
            'description' => $request->description,
            'school_class_id' => $request->school_class_id,
            'subject_id' => $request->subject_id,
            'due_date' => $request->due_date,
            'file_path' => $filePath,
        ]);

        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment updated successfully.');
    }
}

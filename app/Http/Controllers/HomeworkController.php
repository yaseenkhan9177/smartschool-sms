<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class HomeworkController extends Controller
{
    public function index()
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));

        $homeworks = Homework::where('teacher_id', $teacher->id)
            ->with(['schoolClass', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.homework.index', compact('homeworks'));
    }

    public function create()
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));
        $classes = $teacher->schoolClasses;
        $subjects = Subject::all(); // Or filter by teacher's subjects if relation exists

        return view('teacher.homework.create', compact('classes', 'subjects'));
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
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
        ]);

        $homework = Homework::create([
            'school_id' => $teacher->school_id,
            'teacher_id' => $teacher->id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_date' => $request->assigned_date,
            'due_date' => $request->due_date,
        ]);

        ActivityLogger::log('homework', 'Assigned homework "' . \Illuminate\Support\Str::limit($request->title, 20) . '"');

        return redirect()->route('teacher.homework.index')->with('success', 'Homework assigned successfully.');
    }

    public function edit($id)
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));
        $homework = Homework::where('id', $id)->where('teacher_id', $teacher->id)->firstOrFail();

        $classes = $teacher->schoolClasses;
        $subjects = Subject::all();

        return view('teacher.homework.edit', compact('homework', 'classes', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));
        $homework = Homework::where('id', $id)->where('teacher_id', $teacher->id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
        ]);

        $homework->update([
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_date' => $request->assigned_date,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('teacher.homework.index')->with('success', 'Homework updated successfully.');
    }

    public function destroy($id)
    {
        if (!session()->has('teacher_id')) {
            return redirect()->route('login')->with('error', 'Please login as a teacher.');
        }

        $teacher = \App\Models\Teacher::find(session('teacher_id'));
        $homework = Homework::where('id', $id)->where('teacher_id', $teacher->id)->firstOrFail();

        $homework->delete();
        ActivityLogger::log('homework', 'Deleted homework "' . \Illuminate\Support\Str::limit($homework->title, 20) . '"');

        return redirect()->route('teacher.homework.index')->with('success', 'Homework deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentCalendarController extends Controller
{
    public function index()
    {
        $reminders = \App\Models\StudentReminder::where('student_id', \Illuminate\Support\Facades\Auth::guard('student')->id())
            ->get()
            ->map(function ($reminder) {
                return [
                    'id' => $reminder->id,
                    'title' => $reminder->title,
                    'start' => $reminder->reminder_date->toIso8601String(),
                    'description' => $reminder->description,
                    'allDay' => false, // Adjust if needed
                    'className' => 'bg-blue-500 text-white rounded px-2 py-1', // Tailwind classes for FullCalendar
                ];
            });

        return view('student.calendar.index', compact('reminders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'reminder_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        \App\Models\StudentReminder::create([
            'student_id' => \Illuminate\Support\Facades\Auth::guard('student')->id(),
            'title' => $request->title,
            'reminder_date' => $request->reminder_date,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Reminder added successfully.');
    }

    public function destroy($id)
    {
        $reminder = \App\Models\StudentReminder::where('student_id', \Illuminate\Support\Facades\Auth::guard('student')->id())
            ->where('id', $id)
            ->firstOrFail();

        $reminder->delete();

        return redirect()->back()->with('success', 'Reminder deleted successfully.');
    }
}

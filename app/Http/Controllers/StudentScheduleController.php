<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;

class StudentScheduleController extends Controller
{
    public function index()
    {
        $studentId = session('student_id');
        if (!$studentId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $student = Student::find($studentId);

        if (!$student || !$student->class_id) {
            return redirect()->route('student.dashboard')->with('error', 'Class not assigned.');
        }

        $timetables = Timetable::where('school_class_id', $student->class_id)
            ->with(['subject', 'teacher'])
            ->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->orderBy('start_time')
            ->get();

        return view('student.schedule.index', compact('timetables'));
    }
}

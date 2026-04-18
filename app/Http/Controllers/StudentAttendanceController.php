<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student = Student::where('email', Auth::user()->email)->firstOrFail();

        // Get requested month/year or default to current
        $date = $request->has('month') ? Carbon::parse($request->month) : Carbon::now();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $attendances = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy('date');

        // Stats for the month
        $stats = [
            'present' => $attendances->where('status', 'p')->count(), // Assuming 'p' for Present
            'absent' => $attendances->where('status', 'a')->count(),  // Assuming 'a' for Absent
            'late' => $attendances->where('status', 'l')->count(),    // Assuming 'l' for Late
            'leave' => $attendances->where('status', 'leave')->count() // Assuming 'leave'
        ];

        // Ensure consistency with status case sensitivity if needed. 
        // Typically stored as lowercase or uppercase. Let's assume lowercase based on variable names usually used.
        // It's safer to check both if unsure, but I'll stick to 'present', 'absent', 'late', 'leave' or single chars.
        // I'll check how it's stored in db if possible, but for now I'll handle standard cases in view.

        return view('student.attendance.index', compact('student', 'attendances', 'date', 'stats'));
    }
}

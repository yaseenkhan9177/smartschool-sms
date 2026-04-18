<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Default to today if no date provided
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));

        // Fetch teachers for the current school (default id 1)
        // Left join with teacher_attendances to get status for the selected date
        $teachers = Teacher::leftJoin('teacher_attendances', function ($join) use ($date) {
            $join->on('teachers.id', '=', 'teacher_attendances.teacher_id')
                ->where('teacher_attendances.attendance_date', '=', $date);
        })
            ->select(
                'teachers.*',
                'teacher_attendances.status as attendance_status', // present, absent, etc.
                'teacher_attendances.remarks as attendance_remarks'
            )
            ->get();

        $layout = \Illuminate\Support\Facades\Auth::guard('accountant')->check() ? 'layouts.accountant' : 'layouts.admin';

        return view('admin.teacher_attendance.index', compact('teachers', 'date', 'layout'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            // Basic validation for array structure
            'attendance.*.status' => 'required|in:present,absent,late,half_day',
            'attendance.*.remarks' => 'nullable|string',
        ]);

        $date = $request->date;
        $attendances = $request->attendance;

        DB::transaction(function () use ($date, $attendances) {
            foreach ($attendances as $teacherId => $data) {
                TeacherAttendance::updateOrCreate(
                    [
                        'school_id' => 1, // Defaulting to 1 as per system constraints
                        'teacher_id' => $teacherId,
                        'attendance_date' => $date,
                    ],
                    [
                        'status' => $data['status'],
                        'remarks' => $data['remarks'] ?? null,
                    ]
                );
            }
        });

        return back()->with('success', 'Attendance for ' . Carbon::parse($date)->format('M d, Y') . ' has been saved successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeePayment;
use App\Models\StudentFee;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $schoolId = null;
        if (auth()->guard('web')->check()) {
            $schoolId = auth()->guard('web')->user()->school_id;
            $layout = 'layouts.admin';
        } elseif (auth()->guard('accountant')->check()) {
            $schoolId = auth()->guard('accountant')->user()->school_id;
            $layout = 'layouts.accountant';
        } else {
            abort(403);
        }

        // --- 1. Student Reports ---
        $studentQuery = \App\Models\Student::where('school_id', $schoolId);
        $totalStudents = (clone $studentQuery)->count();
        $activeStudents = (clone $studentQuery)->where('status', 'approved')->count();
        $genderWise = (clone $studentQuery)->select('gender', DB::raw('count(*) as total'))->groupBy('gender')->pluck('total', 'gender');

        $classWise = (clone $studentQuery)->select('class_id', DB::raw('count(*) as total'))
            ->with('schoolClass')
            ->groupBy('class_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->schoolClass->name ?? 'Unassigned' => $item->total];
            });

        // --- 2. Fee Reports (Last 30 days vs Overall) ---
        $feeQuery = \App\Models\StudentFee::whereHas('student', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        });

        $totalCollected = \App\Models\FeePayment::whereHas('studentFee.student', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->sum('amount_paid');

        // Late fine is usually saved in StudentFee paid or FeePayment. Assume sum of late_fee in StudentFee for paid ones or just sum late_fee overall? Let's sum late_fee from StudentFee where status is paid
        $fineCollected = (clone $feeQuery)->where('status', 'paid')->sum('late_fee');

        $defaultersCount = (clone $feeQuery)->where('status', '!=', 'paid')->where('due_date', '<', now())->count();

        // --- 3. Exam Reports ---
        // Top 5 students based on total marks in ExamResult
        $topStudents = \App\Models\ExamResult::whereHas('student', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->select('student_id', DB::raw('SUM(obtained_marks) as total_marks'))
            ->with('student')
            ->groupBy('student_id')
            ->orderByDesc('total_marks')
            ->take(5)
            ->get();

        // --- 4. Staff Reports ---
        $teacherQuery = \App\Models\Teacher::where('school_id', $schoolId);
        $totalTeachers = (clone $teacherQuery)->count();

        $todayAttendance = \App\Models\TeacherAttendance::whereHas('teacher', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->whereDate('attendance_date', now()->toDateString())->where('status', 'present')->count();

        // Salary is placeholder for now
        $totalSalaryPaid = 0; // Placeholder

        // --- 5. System Logs ---
        $smsSent = \App\Models\SmsLog::where('school_id', $schoolId)->count();
        $recentActivity = \App\Models\ActivityLog::where('school_id', $schoolId)->latest()->take(10)->get();

        return view('shared.reports.index', compact(
            'layout',
            'totalStudents',
            'activeStudents',
            'genderWise',
            'classWise',
            'totalCollected',
            'fineCollected',
            'defaultersCount',
            'topStudents',
            'totalTeachers',
            'todayAttendance',
            'totalSalaryPaid',
            'smsSent',
            'recentActivity'
        ));
    }
}

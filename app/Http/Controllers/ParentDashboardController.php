<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\SchoolParent $parent */
        $parent = Auth::guard('parent')->user();

        $students = $parent->students()->get();

        $selectedStudentId = $request->get('student_id');

        if ($selectedStudentId) {
            $currentStudent = $students->where('id', $selectedStudentId)->first() ?? $students->first();
        } else {
            $currentStudent = $students->first();
        }

        if ($currentStudent) {
            $currentStudent->load([
                'schoolClass',
                'studentFees',
                'attendances',
                'examResults.examTerm',
                'examResults.subject',
                // Load timetable through class key and ensure schoolClass exists
                'schoolClass.timetables' => function ($query) {
                    $query->with(['subject', 'teacher'])->orderBy('day')->orderBy('start_time');
                },
                'schoolClass.teachers'
            ]);
        }

        // Notices: Targeted at 'parents' or general
        // Assuming simple targeting for now
        $notices = \App\Models\Event::latest()->take(5)->get();  // Simplified for demo, should filter by audience

        // Fetch Homework for the selected student's class
        $homeworks = collect();
        if ($currentStudent && $currentStudent->class_id) {
            $homeworks = \App\Models\Homework::where('class_id', $currentStudent->class_id)
                ->with(['subject', 'teacher'])
                ->orderBy('due_date', 'asc') // Upcoming first
                ->get();
        }

        $complaints = $parent->complaints()->latest()->get();

        // Fetch Escalated Reports for the students
        $studentIds = $students->pluck('id');
        $studentReports = \App\Models\StudentReport::whereIn('student_id', $studentIds)
            ->where('status', 'escalated')
            ->latest()
            ->get();

        return view('parent.dashboard', compact('parent', 'students', 'currentStudent', 'notices', 'homeworks', 'complaints', 'studentReports'));
    }

    public function fees(Request $request)
    {
        /** @var \App\Models\SchoolParent $parent */
        $parent = Auth::guard('parent')->user();

        $students = $parent->students()->get();

        $selectedStudentId = $request->get('student_id');

        if ($selectedStudentId) {
            $currentStudent = $students->where('id', $selectedStudentId)->first() ?? $students->first();
        } else {
            $currentStudent = $students->first();
        }

        if ($currentStudent) {
            $currentStudent->load(['studentFees' => function ($query) {
                $query->latest();
            }]);
        }

        return view('parent.fees', compact('parent', 'students', 'currentStudent'));
    }

    public function attendance(Request $request)
    {
        /** @var \App\Models\SchoolParent $parent */
        $parent = Auth::guard('parent')->user();
        $students = $parent->students()->get();

        $selectedStudentId = $request->get('student_id');
        $currentStudent = $selectedStudentId
            ? ($students->where('id', $selectedStudentId)->first() ?? $students->first())
            : $students->first();

        if ($currentStudent) {
            // Load attendance for the calendar
            $currentStudent->load(['attendances' => function ($query) {
                $query->orderBy('date', 'asc');
            }]);
        }

        return view('parent.attendance', compact('parent', 'students', 'currentStudent'));
    }

    public function exams(Request $request)
    {
        /** @var \App\Models\SchoolParent $parent */
        $parent = Auth::guard('parent')->user();
        $students = $parent->students()->get();

        $selectedStudentId = $request->get('student_id');
        $currentStudent = $selectedStudentId
            ? ($students->where('id', $selectedStudentId)->first() ?? $students->first())
            : $students->first();

        $schedule = collect();
        $activeTerm = null;

        if ($currentStudent) {
            $activeTerm = \App\Models\ExamTerm::where('is_active', true)->first();
            if ($activeTerm) {
                $schedule = \App\Models\ExamSchedule::where('class_id', $currentStudent->class_id)
                    ->where('term_id', $activeTerm->id)
                    ->where('is_published', true)
                    ->with('subject')
                    ->orderBy('exam_date')
                    ->orderBy('start_time')
                    ->get();
            }
        }

        return view('parent.exams', compact('parent', 'students', 'currentStudent', 'activeTerm', 'schedule'));
    }

    public function storeLeave(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'nullable|string',
        ]);

        /** @var \App\Models\SchoolParent $parent */
        $parent = Auth::guard('parent')->user();

        // Ensure student belongs to parent
        $student = $parent->students()->where('id', $request->student_id)->firstOrFail();

        \App\Models\LeaveApplication::create([
            'school_id' => $student->school_id,
            'student_id' => $student->id,
            'parent_id' => $parent->id,
            'type' => $request->type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Leave application submitted successfully.');
    }

    public function storeComplaint(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        /** @var \App\Models\SchoolParent $parent */
        $parent = Auth::guard('parent')->user();

        \App\Models\Complaint::create([
            'school_id' => $parent->school_id, // Assuming parent tracks school_id directly or via student
            'parent_id' => $parent->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Message sent to admin successfully.');
    }
}

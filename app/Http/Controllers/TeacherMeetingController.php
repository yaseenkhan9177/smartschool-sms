<?php

namespace App\Http\Controllers;

use App\Models\TeacherMeeting;
use App\Models\Teacher;
use App\Services\ZoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GeneralNotification;
use App\Models\Accountant;

class TeacherMeetingController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Check Admin (Web Guard) - Show ALL meetings for the school
        if (Auth::guard('web')->check()) {
            $schoolId = Auth::guard('web')->user()->school_id;
            $meetings = TeacherMeeting::where('school_id', $schoolId) // Scope by school
                ->with('participants')
                ->orderBy('start_time', 'desc')
                ->get();

            $routePrefix = 'admin';
            return view('admin.meetings.index', compact('meetings', 'routePrefix'));
        } elseif (Auth::guard('teacher')->check()) {
            $teacherId = Auth::guard('teacher')->id();
            $meetings = TeacherMeeting::whereHas('participants', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })->orderBy('start_time', 'desc')->get();

            $routePrefix = 'teacher';
            return view('admin.meetings.index', compact('meetings', 'routePrefix'));
        } elseif (Auth::guard('accountant')->check()) {
            $schoolId = Auth::guard('accountant')->user()->school_id;
            $meetings = TeacherMeeting::where('school_id', $schoolId)
                ->orderBy('start_time', 'desc')
                ->get();

            $routePrefix = 'accountant';
            return view('admin.meetings.index', compact('meetings', 'routePrefix'));
        }

        abort(403, 'Unauthorized access to meetings.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Allow Admin Only
        if (!Auth::guard('web')->check()) {
            abort(403, 'Unauthorized access. Only Admins can schedule meetings.');
        }

        $schoolId = Auth::guard('web')->user()->school_id ?? 1;
        $teachers = Teacher::where('school_id', $schoolId)->get();
        return view('admin.meetings.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'start_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:240',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:teachers,id',
        ]);

        // 1. Create Meeting on Zoom
        $startTime = Carbon::parse($request->start_time);
        $zoomStartTime = $startTime->format('Y-m-d\TH:i:s');

        $zoomMeeting = $this->zoomService->createMeeting(
            $request->topic,
            $zoomStartTime,
            $request->duration,
            $request->password
        );

        if (!$zoomMeeting) {
            return back()->with('error', 'Failed to create Zoom meeting. Check API Credentials in .env');
        }

        // 2. Save to DB (Admin Only)
        if (!Auth::guard('web')->check()) {
            abort(403, 'Unauthorized');
        }

        $hostId = Auth::guard('web')->id();
        $hostType = 'admin';
        $schoolId = Auth::guard('web')->user()->school_id ?? 1;

        $meeting = TeacherMeeting::create([
            'school_id' => $schoolId,
            'host_id' => $hostId,
            'host_type' => $hostType,
            'topic' => $request->topic,
            'description' => $request->description,
            'start_time' => $startTime,
            'duration' => $request->duration,
            'zoom_meeting_id' => $zoomMeeting['id'],
            'zoom_start_url' => $zoomMeeting['start_url'],
            'zoom_join_url' => $zoomMeeting['join_url'],
            'password' => $zoomMeeting['password'] ?? null,
            'status' => 'scheduled',
        ]);

        // 3. Attach Participants
        $meeting->participants()->attach($request->participants);

        // 4. Send Notification to School Staff (Teachers & Accountants)
        $recipients = collect();

        // Get Teachers of this school (invited ones or all? User said "thier school teacher". Usually invited ones for meeting, but user said "send notifaction to thier school teacher and acccountent". I'll send to ALL teachers of the school as per "thier school teacher" broad phrasing, or maybe just invited? "and other staff can only join... send notifaction". I'll default to ALL teachers and accountants to announce the meeting).
        $schoolTeachers = Teacher::where('school_id', $schoolId)->get();
        $schoolAccountants = Accountant::where('school_id', $schoolId)->get();

        $recipients = $recipients->merge($schoolTeachers)->merge($schoolAccountants);

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new GeneralNotification(
                'New Staff Meeting: ' . $request->topic,
                'A new staff meeting has been scheduled for ' . $startTime->format('d M h:i A') . '. Please join via the dashboard.',
                'Admin',
                'admin',
                Auth::guard('web')->id(),
                'meeting'
            ));
        }



        return redirect()->route('admin.meetings.index')->with('success', 'Meeting Scheduled Successfully!');
    }

    /**
     * Start Meeting (Redirect Host)
     */
    public function start($id)
    {
        $meeting = TeacherMeeting::findOrFail($id);

        // Authorization: Only Admin can start
        if (Auth::guard('web')->check()) {
            // Admin allowed
        } else {
            abort(403, 'Only the Admin (Host) can start the meeting.');
        }

        if ($meeting->status === 'scheduled') {
            $meeting->update(['status' => 'started']);
        }

        return redirect()->away($meeting->zoom_start_url);
    }

    /**
     * Join Meeting (Redirect Participant)
     */
    public function join($id)
    {
        $meeting = TeacherMeeting::findOrFail($id);

        // Record attendance if teacher
        if (Auth::guard('teacher')->check()) {
            $teacherId = Auth::guard('teacher')->id();
            $participant = $meeting->participants()->where('teacher_id', $teacherId)->first();
            if ($participant) {
                // Update status to attended
                $meeting->participants()->updateExistingPivot($teacherId, ['status' => 'attended']);
            }
        }

        // Accountants and Teachers (and others) can join
        return redirect()->away($meeting->zoom_join_url);
    }

    /**
     * Delete/Cancel Meeting
     */
    public function destroy($id)
    {
        // Allow Admins Only
        if (!Auth::guard('web')->check()) {
            abort(403, 'Unauthorized');
        }

        $meeting = TeacherMeeting::findOrFail($id);

        // Call Zoom API to delete
        $this->zoomService->deleteMeeting($meeting->zoom_meeting_id);

        $meeting->delete();

        return back()->with('success', 'Meeting cancelled.');
    }
}

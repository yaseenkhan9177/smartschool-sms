<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Accountant;
use App\Models\SchoolClass;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = null;
        $layout = 'layouts.app'; // Fallback
        $readRouteName = 'student.notifications.read'; // Default

        // 1. Detect Context based on Route (Strict Layout & User resolution)
        if (request()->routeIs('student.*')) {
            $user = Student::find(session('student_id'));
            $layout = 'layouts.student';
            $readRouteName = 'student.notifications.read';
        } elseif (request()->routeIs('teacher.*')) {
            $user = Teacher::find(session('teacher_id'));
            $layout = 'layouts.teacher';
            $readRouteName = 'teacher.notifications.read';
        } elseif (request()->routeIs('accountant.*')) {
            $user = Accountant::find(session('accountant_id'));
            $layout = 'layouts.accountant';
            $readRouteName = 'accountant.notifications.read';
        } elseif (request()->routeIs('parent.*')) {
            $layout = 'layouts.app'; // Fallback layout
            $parentPhone = session('parent_phone');
            $user = Student::where('parent_phone', $parentPhone)->first();
        } elseif (request()->routeIs('admin.*') || request()->routeIs('school admin.*') || auth()->guard('web')->check()) {
            // Admin Context
            $user = auth()->guard('web')->user();
            $layout = 'layouts.admin';
            $readRouteName = 'admin.notifications.read';
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view notifications.');
        }

        // 2. Mark ALL Unread as Read (Clear the Badge automatically on visit)
        $user->unreadNotifications()->update(['read_at' => now()]);

        // 3. Fetch Notifications (All)
        $notifications = $user->notifications()->latest()->paginate(10);

        return view('notifications.index', compact('notifications', 'layout', 'readRouteName'));
    }

    /**
     * Get allowed sender types based on user role
     */
    private function getAllowedSenderTypes($userRole)
    {
        switch ($userRole) {
            case 'student':
                // Students see notifications from teachers, accountants, and admins
                return ['teacher', 'accountant', 'admin'];
            case 'teacher':
                // Teachers see notifications from students, accountants, and admins
                return ['student', 'accountant', 'admin'];
            case 'accountant':
                // Accountants see notifications from students, teachers, and admins
                return ['student', 'teacher', 'admin'];
            case 'admin':
                // Admins see all notifications
                return ['student', 'teacher', 'accountant', 'admin'];
            default:
                return [];
        }
    }

    public function markAsRead($id)
    {
        $user = null;
        // Resolve user based on session similar to index
        if (auth()->guard('web')->check()) {
            $user = auth()->guard('web')->user();
        } elseif (session()->has('accountant_id')) {
            $user = Accountant::find(session('accountant_id'));
        } elseif (session()->has('student_id')) {
            $user = Student::find(session('student_id'));
        } elseif (session()->has('teacher_id')) {
            $user = Teacher::find(session('teacher_id'));
        }

        if ($user) {
            $notification = $user->notifications()->find($id);
            if ($notification) {
                $notification->markAsRead();
                return back()->with('success', 'Notification marked as read.');
            }
        }

        return back()->with('error', 'Notification not found.');
    }

    public function getStudentsByClass($classId)
    {
        $students = Student::where('class_id', $classId)
            ->orderBy('name')
            ->select('id', 'name', 'email') // Select needed fields
            ->get();
        return response()->json($students);
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $students = collect(); // Load via AJAX for specific student
        $teachers = Teacher::orderBy('name')->get(); // This is still a risk if many teachers, but better than both. 
        // Let's limit teachers too if there are many.
        if (Teacher::count() > 100) {
            $teachers = collect();
        }

        $layout = 'layouts.admin'; // Default
        if (request()->routeIs('accountant.*')) {
            $layout = 'layouts.accountant';
        } elseif (request()->routeIs('student.*')) {
            $layout = 'layouts.student';
        }

        return view('notifications.create', compact('classes', 'students', 'teachers', 'layout'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'audience' => 'required|string',
            'class_id' => 'required_if:audience,class',
            'student_id' => 'required_if:audience,student',
            'teacher_id' => 'required_if:audience,teacher',
        ]);

        $users = collect();
        $senderName = 'System';
        $senderType = null;
        $senderId = null;

        // Determine Sender
        if (auth()->guard('web')->check()) {
            $senderName = 'Admin';
            $senderType = 'admin';
            $senderId = auth()->user()->id;
        } elseif (session()->has('accountant_id')) {
            $senderName = session('accountant_name') . ' (Accountant)';
            $senderType = 'accountant';
            $senderId = session('accountant_id');
        } elseif (session()->has('student_id')) {
            $senderName = session('student_name') . ' (Student)';
            $senderType = 'student';
            $senderId = session('student_id');
        } elseif (session()->has('teacher_id')) {
            $senderName = session('teacher_name') . ' (Teacher)';
            $senderType = 'teacher';
            $senderId = session('teacher_id');
        }

        $senderData = [
            'title' => $request->title,
            'message' => $request->message,
            'senderName' => $senderName,
            'senderType' => $senderType,
            'senderId' => $senderId
        ];

        $sendToQuery = null;

        switch ($request->audience) {
            case 'all_students':
                $sendToQuery = Student::query();
                break;
            case 'all_teachers':
                $sendToQuery = Teacher::query();
                break;
            case 'student':
                $sendToQuery = Student::where('id', $request->student_id);
                break;
            case 'teacher':
                $sendToQuery = Teacher::where('id', $request->teacher_id);
                break;
            case 'class':
                $sendToQuery = Student::where('class_id', $request->class_id);
                break;
            case 'everyone':
                // Send separately to keep queries isolated and clean
                $this->sendChunkedNotifications(Student::query(), $senderData);
                $this->sendChunkedNotifications(Teacher::query(), $senderData);
                return back()->with('success', 'Mass notification queued for delivery.');
            case 'accountant':
                $sendToQuery = Accountant::query();
                break;
        }

        if (!$sendToQuery) {
            return back()->with('error', 'Invalid audience selected.');
        }

        $count = $sendToQuery->count();
        if ($count === 0) {
            return back()->with('error', 'No recipients found for the selected audience.');
        }

        $this->sendChunkedNotifications($sendToQuery, $senderData);

        return back()->with('success', 'Notification sent successfully to ' . $count . ' recipients.');
    }

    /**
     * Helper to send notifications in memory-safe batches
     */
    private function sendChunkedNotifications($query, $senderData)
    {
        $query->chunk(100, function ($users) use ($senderData) {
            Notification::send($users, new GeneralNotification(
                $senderData['title'],
                $senderData['message'],
                $senderData['senderName'],
                $senderData['senderType'],
                $senderData['senderId']
            ));
        });
    }

    public function history()
    {
        // Admin only usually
        /** @var \Illuminate\Pagination\LengthAwarePaginator $notifications */
        $notifications = \Illuminate\Support\Facades\DB::table('notifications')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Manual hydration or just pass raw data
        // We need to decode the JSON data column
        $notifications->through(function ($n) {
            $n->data = json_decode($n->data, true);
            return $n;
        });

        return view('admin.notifications.history', compact('notifications'));
    }
}

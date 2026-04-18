<?php

namespace App\Http\Controllers;

use App\Models\OnlineClass;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Services\ZoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class OnlineClassController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    public function index()
    {
        $classes = OnlineClass::where('teacher_id', Auth::id())
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->get();

        return view('teacher.online_classes.index', compact('classes'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all(); // Ideally filter by teacher's subjects
        return view('teacher.online_classes.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'topic' => 'required|string',
            'start_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:240',
            'slides' => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx|max:10240', // Max 10MB
        ]);

        try {
            // Handle File Upload
            $slidesPath = null;
            if ($request->hasFile('slides')) {
                $file = $request->file('slides');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('slides'), $filename);
                $slidesPath = 'slides/' . $filename;
            }

            // Create Meeting on Zoom
            $meetingData = $this->zoomService->createMeeting(
                $request->topic,
                \Carbon\Carbon::parse($request->start_time)->toIso8601String(),
                $request->duration
            );

            // Save to Database
            OnlineClass::create([
                'school_id' => Auth::user()->school_id,
                'teacher_id' => Auth::id(),
                'school_class_id' => $request->school_class_id,
                'subject_id' => $request->subject_id,
                'topic' => $request->topic,
                'start_time' => $request->start_time,
                'duration' => $request->duration,
                'meeting_id' => $meetingData['id'],
                'meeting_password' => $meetingData['password'] ?? null,
                'join_url' => $meetingData['join_url'],
                'start_url' => $meetingData['start_url'] ?? null,
                'slides_path' => $slidesPath,
            ]);


            ActivityLogger::log('online_class', 'Scheduled online class "' . $request->topic . '"');

            return redirect()->route('teacher.online-classes.index')->with('success', 'Online Class scheduled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to schedule class: ' . $e->getMessage());
        }
    }

    public function destroy(OnlineClass $onlineClass)
    {
        // Ideally verify teacher owns this class
        if ($onlineClass->teacher_id != Auth::id()) {
            abort(403);
        }

        // TODO: Delete from Zoom API as well

        $onlineClass->delete();
        return back()->with('success', 'Class cancelled successfully.');
    }
}

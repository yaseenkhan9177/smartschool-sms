<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::latest()->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
            'target_audience' => 'required|array',
            'target_audience.*' => 'in:student,teacher,accountant,admin',
        ]);

        $admin = Auth::guard('web')->user();

        Event::create([
            'school_id' => $admin->id, // Admin ID IS the school_id in this system architecture
            'title' => $request->title,
            'event_date' => $request->event_date,
            'description' => $request->description,
            'target_audience' => $request->target_audience,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Event created successfully.',
                'redirect_url' => route('admin.events.index')
            ]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
            'target_audience' => 'required|array',
            'target_audience.*' => 'in:student,teacher,accountant,admin',
        ]);



        $event->update([
            'title' => $request->title,
            'event_date' => $request->event_date,
            'description' => $request->description,
            'target_audience' => $request->target_audience,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully.',
                'redirect_url' => route('admin.events.index')
            ]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OnlineClass;

class StudentOnlineClassController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Fetch classes for the student's assigned class
        // Showing future classes and recent past classes (e.g. last 24 hours) for reference
        $onlineClasses = OnlineClass::where('school_class_id', $student->class_id)
            ->where('start_time', '>=', now()->subHours(24))
            ->orderBy('start_time', 'asc') // Upcoming first
            ->with(['teacher', 'subject'])
            ->paginate(10);

        return view('student.online_classes.index', compact('onlineClasses'));
    }
}

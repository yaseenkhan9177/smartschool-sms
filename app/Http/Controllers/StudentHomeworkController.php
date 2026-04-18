<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentHomeworkController extends Controller
{
    public function index()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('login');
        }

        $student = Auth::guard('student')->user();

        // Fetch homework for the student's class
        // Ordered by due_date ascending (closest due date first)
        $homeworks = Homework::where('class_id', $student->class_id)
            ->with(['subject', 'teacher'])
            ->orderBy('due_date', 'asc')
            ->get();

        return view('student.homework.index', compact('homeworks'));
    }
}

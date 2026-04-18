<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ExamTerm;
use App\Models\ExamResult;

class PublicResultController extends Controller
{
    /**
     * Handle the result search request.
     */
    public function search(Request $request)
    {
        $request->validate([
            'roll_no' => 'required|string',
        ]);

        $rollNo = $request->roll_no;

        // Find student by roll number
        // Assuming roll_number is consistent. Note: SchoolScope is global, so this might fail if not careful.
        // But for public search, we typically want to find across valid schools or default scope.
        // Public/Guest doesn't have school_id.
        // We might need to use withoutGlobalScope if we want to search broadly, or contextually.
        // However, existing roll numbers might be unique within a school only?
        // Let's assume unique globally for now, or just search.

        $student = Student::withoutGlobalScope(\App\Models\Scopes\SchoolScope::class)
            ->where('roll_number', $rollNo)
            ->first();

        if (!$student) {
            return back()->with('error', 'Student with Roll Number ' . $rollNo . ' not found.');
        }

        // Fetch results for the student
        // Group by Term
        $results = ExamResult::withoutGlobalScope(\App\Models\Scopes\SchoolScope::class)
            ->where('student_id', $student->id)
            ->with(['term', 'subject'])
            ->get()
            ->groupBy('term_id');

        return view('public.result', compact('student', 'results'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolClass;
use App\Models\ExamTerm;
use App\Models\Subject;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\SchoolClassTeacher;

class TeacherMarksController extends Controller
{


    /**
     * Show the marks entry grid.
     */
    /**
     * Show the marks entry grid (Unified View).
     */
    public function create(Request $request)
    {
        /** @var \App\Models\Teacher $teacher */
        $teacher = Auth::guard('teacher')->user();

        // Always load dropdown data
        $classes = $teacher->schoolClasses()->with('subjects')->get();
        $terms = ExamTerm::where('school_id', $teacher->school_id)
            ->where('is_active', true)
            ->get();

        $students = [];
        $existingResults = [];
        $term = null;
        $class = null;
        $subject = null;
        $total_marks = 100;

        // If parameters are present, load the grid data
        if ($request->has(['term_id', 'class_id', 'subject_id'])) {
            $term = ExamTerm::findOrFail($request->term_id);
            $class = SchoolClass::findOrFail($request->class_id);
            $subject = Subject::findOrFail($request->subject_id);

            // Fetch students in this class
            $students = Student::where('class_id', $class->id)->get();

            // Fetch existing marks if any
            $resultsCollection = ExamResult::where('term_id', $term->id)
                ->where('class_id', $class->id)
                ->where('subject_id', $subject->id)
                ->get();

            $existingResults = $resultsCollection->keyBy('student_id');

            // Try to infer total marks from existing results if any, otherwise default to 100
            if ($resultsCollection->isNotEmpty()) {
                $total_marks = $resultsCollection->first()->total_marks;
            }
        }

        return view('teacher.marks.entry', compact('classes', 'terms', 'students', 'existingResults', 'term', 'class', 'subject', 'total_marks'));
    }

    /**
     * Store the marks.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'term_id' => 'required',
            'class_id' => 'required',
            'subject_id' => 'required',
            'marks' => 'nullable|array',
            'remarks' => 'nullable|array',
            'total_marks' => 'required|numeric|min:1',
        ]);

        $teacher = Auth::guard('teacher')->user();

        if (isset($data['marks'])) {
            foreach ($data['marks'] as $studentId => $obtained) {
                // If marks are null/empty, we skip saving unless it's an update to remove marks?
                // For now, let's assume empty input means no grade for that student.
                if ($obtained === null || $obtained === '') continue;

                $remark = $data['remarks'][$studentId] ?? null;

                ExamResult::updateOrCreate(
                    [
                        'school_id' => $teacher->school_id,
                        'term_id' => $data['term_id'],
                        'class_id' => $data['class_id'],
                        'subject_id' => $data['subject_id'],
                        'student_id' => $studentId,
                    ],
                    [
                        'obtained_marks' => $obtained,
                        'total_marks' => $data['total_marks'],
                        'grade' => ExamResult::calculateGrade($obtained, $data['total_marks']),
                        'remarks' => $remark,
                    ]
                );
            }
        }

        if (isset($data['marks']) && count($data['marks']) > 0) {
            $classId = $data['class_id'];
            $class = \App\Models\SchoolClass::find($classId);
            $className = $class ? $class->name : 'Unknown Class';
            \App\Helpers\ActivityLogger::log('result', "Marks uploaded for Class {$className}.");
        }

        return redirect()->route('teacher.marks.create', [
            'term_id' => $data['term_id'],
            'class_id' => $data['class_id'],
            'subject_id' => $data['subject_id']
        ])->with('success', 'Marks saved successfully!');
    }

    public function printResult($studentId, $termId)
    {
        $student = Student::with(['schoolClass', 'school'])->findOrFail($studentId);
        $term = ExamTerm::findOrFail($termId);

        // Fetch results with subject names
        $results = ExamResult::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->with('subject')
            ->get();

        // Calculate totals
        $totalObtained = $results->sum('obtained_marks');
        $totalMax = $results->sum('total_marks');

        $percentage = ($totalMax > 0) ? ($totalObtained / $totalMax) * 100 : 0;

        // Overall Grade
        $overallGrade = ExamResult::calculateGrade($totalObtained, $totalMax);

        return view('exams.result_card', compact('student', 'term', 'results', 'totalObtained', 'totalMax', 'percentage', 'overallGrade'));
    }
}

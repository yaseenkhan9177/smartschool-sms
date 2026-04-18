<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccountantCertificateController extends Controller
{
    public function index()
    {
        $accountant = \Illuminate\Support\Facades\Auth::guard('accountant')->user();

        // Fetch only certificates of students in the same school
        $certificates = Certificate::with(['student', 'template'])
            ->whereHas('student', function ($query) use ($accountant) {
                $query->where('school_id', $accountant->school_id);
            })
            ->latest()
            ->paginate(10);

        return view('accountant.certificates.index', compact('certificates'));
    }

    public function show($id)
    {
        $accountant = \Illuminate\Support\Facades\Auth::guard('accountant')->user();

        $certificate = Certificate::with(['student', 'template'])->findOrFail($id);

        if ($certificate->student->school_id !== $accountant->school_id) {
            abort(403, 'Unauthorized');
        }

        $content = $this->generateContent($certificate->template->body, $certificate->student, $certificate);

        return view('accountant.certificates.print', compact('certificate', 'content'));
    }

    // Helper to replace placeholders
    private function generateContent($templateBody, $student, $certificate = null)
    {
        $replacements = [
            '{{student_name}}' => $student->name,
            '{{father_name}}' => $student->father_name ?? $student->parent->father_name ?? 'N/A',
            '{{roll_no}}' => $student->roll_no ?? 'N/A',
            '{{class}}' => $student->schoolClass->name ?? 'N/A',
            '{{dob}}' => $student->custom1 ?? 'N/A',
            '{{admission_date}}' => $student->admission_date ? Carbon::parse($student->admission_date)->format('d M Y') : 'N/A',
            '{{issue_date}}' => $certificate ? $certificate->issue_date->format('d M Y') : Carbon::today()->format('d M Y'),
            '{{principal_name}}' => 'Principal', // Accountant may not have a "Principal" auth context
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $templateBody);
    }
}

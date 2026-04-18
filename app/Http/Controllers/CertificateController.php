<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['student', 'template'])->latest()->paginate(10);
        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $templates = CertificateTemplate::where('is_active', true)->get();
        return view('admin.certificates.create', compact('classes', 'templates'));
    }

    // API to get students by class (reusing existing or creating new simple one)
    public function getStudents($classId)
    {
        $students = Student::where('class_id', $classId)->select('id', 'name', 'roll_no')->get();
        return response()->json($students);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'template_id' => 'required|exists:certificate_templates,id',
        ]);

        $student = Student::with('schoolClass')->findOrFail($request->student_id);
        $template = CertificateTemplate::findOrFail($request->template_id);

        // Generate Temporary/Preview Data
        $certificateContent = $this->generateContent($template->body, $student);
        $issueDate = Carbon::today()->format('d M Y');

        return view('admin.certificates.preview', compact('student', 'template', 'certificateContent', 'issueDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'template_id' => 'required|exists:certificate_templates,id',
            // 'issue_date' could be added if users want to backdate
        ]);

        $student = Student::with('schoolClass')->findOrFail($request->student_id);
        $template = CertificateTemplate::findOrFail($request->template_id);

        // Generate Unique Certificate Number: CERT-{YEAR}-{RANDOM}
        $certNo = 'CERT-' . date('Y') . '-' . strtoupper(Str::random(6));

        // Create Snapshot of data at time of issuance
        $dataSnapshot = [
            'student_name' => $student->name,
            'class' => $student->schoolClass->name ?? 'N/A',
            'roll_no' => $student->roll_no,
            'father_name' => $student->parent->father_name ?? $student->father_name ?? 'N/A', // Fallback
        ];

        $certificate = Certificate::create([
            'student_id' => $student->id,
            'template_id' => $template->id,
            'certificate_no' => $certNo,
            'issue_date' => Carbon::today(),
            'status' => 'issued',
            'issued_by' => auth()->id(),
            'data_snapshot' => $dataSnapshot
        ]);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate issued successfully! Certificate No: ' . $certNo);
    }

    public function show($id)
    {
        $certificate = Certificate::with(['student', 'template'])->findOrFail($id);

        // Re-generate content using the Template but potentially using Snapshot data if we wanted STRICT immutability.
        // For now, let's use current template text but maybe we should have stored the *text* too?
        // In this implementation, we re-parse the template. If template changes, reprint changes.
        // To be safer, usually we store the final HTML. But for now dynamic is okay for a simple system.

        $content = $this->generateContent($certificate->template->body, $certificate->student, $certificate);

        return view('admin.certificates.print', compact('certificate', 'content'));
    }

    // Helper to replace placeholders
    private function generateContent($templateBody, $student, $certificate = null)
    {
        $replacements = [
            '{{student_name}}' => $student->name,
            '{{father_name}}' => $student->father_name ?? $student->parent->father_name ?? 'N/A',
            '{{roll_no}}' => $student->roll_no ?? 'N/A',
            '{{class}}' => $student->schoolClass->name ?? 'N/A',
            '{{dob}}' => $student->custom1 ?? 'N/A', // Assuming custom1 is DOB or similar, adjust if real field exists
            '{{admission_date}}' => $student->admission_date ? Carbon::parse($student->admission_date)->format('d M Y') : 'N/A',
            '{{issue_date}}' => $certificate ? $certificate->issue_date->format('d M Y') : Carbon::today()->format('d M Y'),
            '{{principal_name}}' => auth()->user()->name ?? 'Principal',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $templateBody);
    }
}

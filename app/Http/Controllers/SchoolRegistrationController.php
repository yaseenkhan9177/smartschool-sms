<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchoolRegistrationController extends Controller
{
    public function showTerms()
    {
        $terms = \App\Models\PlatformTerm::where('active', true)->latest()->first();
        if (!$terms) {
            // Seed default terms if none exist
            $content = "Platform Agreement\n\nSchool Management System\n\nPlease read the following terms carefully before registering your school on this platform.\n\n1. Platform Purpose\n\nThis platform provides a School Management System (SMS) designed to help schools manage students, staff, attendance, exams, fees, and academic records digitally.\n\nBy registering your school, you agree to use the system only for educational and administrative purposes.\n\n2. Account Approval\n\nSchool registration is not automatic.\n\nAll school applications are reviewed by the platform administrator.\n\nThe platform owner reserves the right to approve or reject any registration request without prior notice.\n\n3. Data Responsibility\n\nThe school is fully responsible for the accuracy, legality, and security of the data entered into the system.\n\nThe platform owner is not responsible for incorrect, lost, or misleading data entered by school staff.\n\n4. User Access & Security\n\nLogin credentials provided to the school are confidential.\n\nThe school is responsible for managing access of its staff, teachers, and users.\n\nAny activity performed using the school’s account will be considered the responsibility of the school.\n\n5. System Availability\n\nThe platform aims to provide continuous service, but temporary downtime may occur due to maintenance or technical issues.\n\nThe platform owner is not liable for losses caused by service interruptions.\n\n6. Usage Limitations\n\nThe system must not be used for illegal, unethical, or non-educational purposes.\n\nAttempting to misuse, modify, or damage the platform is strictly prohibited.\n\n7. Demo / Trial Usage (If Applicable)\n\nDemo or trial accounts may have limited features.\n\nDemo data may be reset or deleted without notice.\n\n8. Termination of Access\n\nThe platform owner reserves the right to suspend or terminate a school’s access if these terms are violated.\n\nUpon termination, access to the system and stored data may be restricted.\n\n9. Changes to Terms\n\nThese terms may be updated at any time by the platform administrator.\n\nContinued use of the platform after updates means acceptance of the revised terms.\n\n10. Agreement Acceptance\n\nBy clicking “I Agree” and submitting the registration form, the school confirms that it has read, understood, and accepted all the terms and conditions stated above.\n\nLast Updated: " . date('F d, Y') . "\nPlatform Owner: Muhammad Yaseen";

            $terms = \App\Models\PlatformTerm::create([
                'content' => $content,
                'version' => '1.0',
                'active' => true
            ]);
        }
        return view('auth.register_school_terms', compact('terms'));
    }

    public function acceptTerms(Request $request)
    {
        $request->validate([
            'terms_version' => 'required',
            'agree' => 'accepted'
        ]);

        session(['school_registration.terms_accepted' => true]);
        session(['school_registration.terms_version' => $request->terms_version]);

        return redirect()->route('school.register.form');
    }

    public function showRegistrationForm()
    {
        if (!session('school_registration.terms_accepted')) {
            return redirect()->route('school.register.terms')->with('error', 'Please accept the terms and conditions first.');
        }

        return view('auth.register_school_form');
    }

    public function storeRequest(Request $request)
    {
        if (!session('school_registration.terms_accepted')) {
            return redirect()->route('school.register.terms');
        }

        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:schools,email|unique:school_requests,email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'student_count' => 'nullable|integer',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('school_logos', 'public');
        }

        // Create Request
        $schoolRequest = \App\Models\SchoolRequest::create([
            'school_name' => $validated['school_name'],
            'owner_name' => $validated['owner_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'student_count' => $validated['student_count'],
            'logo' => $logoPath,
            'status' => 'pending',
        ]);

        // Record Acceptance
        \App\Models\PlatformTermsAcceptance::create([
            'school_email' => $validated['email'],
            'accepted_at' => now(),
            'ip_address' => $request->ip(),
            'terms_version' => session('school_registration.terms_version', '1.0'),
            'request_id' => $schoolRequest->id,
        ]);

        // Clear session
        session()->forget(['school_registration.terms_accepted', 'school_registration.terms_version']);

        return redirect()->route('school.register.success');
    }

    public function success()
    {
        return view('auth.register_school_success');
    }
}

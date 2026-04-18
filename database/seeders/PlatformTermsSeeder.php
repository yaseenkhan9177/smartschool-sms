<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformTermsSeeder extends Seeder
{
    public function run()
    {
        $content = "Platform Agreement

School Management System

Please read the following terms carefully before registering your school on this platform.

1. Platform Purpose

This platform provides a School Management System (SMS) designed to help schools manage students, staff, attendance, exams, fees, and academic records digitally.

By registering your school, you agree to use the system only for educational and administrative purposes.

2. Account Approval

School registration is not automatic.

All school applications are reviewed by the platform administrator.

The platform owner reserves the right to approve or reject any registration request without prior notice.

3. Data Responsibility

The school is fully responsible for the accuracy, legality, and security of the data entered into the system.

The platform owner is not responsible for incorrect, lost, or misleading data entered by school staff.

4. User Access & Security

Login credentials provided to the school are confidential.

The school is responsible for managing access of its staff, teachers, and users.

Any activity performed using the school’s account will be considered the responsibility of the school.

5. System Availability

The platform aims to provide continuous service, but temporary downtime may occur due to maintenance or technical issues.

The platform owner is not liable for losses caused by service interruptions.

6. Usage Limitations

The system must not be used for illegal, unethical, or non-educational purposes.

Attempting to misuse, modify, or damage the platform is strictly prohibited.

7. Demo / Trial Usage (If Applicable)

Demo or trial accounts may have limited features.

Demo data may be reset or deleted without notice.

8. Termination of Access

The platform owner reserves the right to suspend or terminate a school’s access if these terms are violated.

Upon termination, access to the system and stored data may be restricted.

9. Changes to Terms

These terms may be updated at any time by the platform administrator.

Continued use of the platform after updates means acceptance of the revised terms.

10. Agreement Acceptance

By clicking “I Agree” and submitting the registration form, the school confirms that it has read, understood, and accepted all the terms and conditions stated above.

Last Updated: " . date('F d, Y') . "
Platform Owner: Muhammad Yaseen";

        \App\Models\PlatformTerm::create([
            'content' => $content,
            'version' => '1.1',
            'active' => true
        ]);

        $this->command->info('Platform Terms Updated Successfully.');
    }
}

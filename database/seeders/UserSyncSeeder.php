<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Accountant;

class UserSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password'); // Default password for synced users

        // Sync Students
        $students = Student::all();
        foreach ($students as $student) {
            $existingUser = User::where('email', $student->email)->first();
            if (!$existingUser) {
                User::create([
                    'name' => $student->name,
                    'email' => $student->email,
                    'password' => $student->password ?? $password, // Use existing hash or default
                    'role' => 'student',
                    'school_name' => 'Workshop School', // Default or fetch from relation
                    'status' => 'active',
                ]);
                $this->command->info("Synced Student: {$student->email}");
            }
        }

        // Sync Teachers
        $teachers = Teacher::all();
        foreach ($teachers as $teacher) {
            $existingUser = User::where('email', $teacher->email)->first();
            if (!$existingUser) {
                User::create([
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'password' => $teacher->password ?? $password,
                    'role' => 'teacher',
                    'school_name' => 'Workshop School',
                    'status' => 'active',
                ]);
                $this->command->info("Synced Teacher: {$teacher->email}");
            }
        }

        // Sync Accountants
        $accountants = Accountant::all();
        foreach ($accountants as $accountant) {
            $existingUser = User::where('email', $accountant->email)->first();
            if (!$existingUser) {
                User::create([
                    'name' => $accountant->name,
                    'email' => $accountant->email,
                    'password' => $accountant->password ?? $password,
                    'role' => 'accountant',
                    'school_name' => 'Workshop School',
                    'status' => 'active',
                ]);
                $this->command->info("Synced Accountant: {$accountant->email}");
            }
        }

        // Also ensure Yaseen is present as requested specifically
        $yaseen = User::where('email', 'yaseen@gmail.com')->first();
        if (!$yaseen) {
            User::create([
                'name' => 'Yaseen',
                'email' => 'yaseen@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'active',
            ]);
            $this->command->info("Created Manual User: yaseen@gmail.com");
        }
    }
}

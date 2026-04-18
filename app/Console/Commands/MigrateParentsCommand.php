<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\SchoolParent;
use Illuminate\Support\Facades\Hash;

class MigrateParentsCommand extends Command
{
    protected $signature = 'migrate:parents';
    protected $description = 'Migrate existing student parent data into Parents table';

    public function handle()
    {
        $this->info('Starting migration of parents...');

        // Use withoutGlobalScopes to ensure we get all students, ignoring any auth scopes in CLI
        $students = Student::withoutGlobalScopes()
            ->whereNotNull('parent_phone')
            ->where('parent_phone', '!=', '')
            ->whereNull('parent_id')
            ->get();

        $count = 0;

        foreach ($students as $student) {
            // Check if school_id is present
            if (!$student->school_id) {
                $this->warn("Skipping student {$student->id} (No School ID)");
                continue;
            }

            // Check if parent already exists by phone AND school_id
            $parent = SchoolParent::where('phone', $student->parent_phone)
                ->where('school_id', $student->school_id)
                ->first();

            if (!$parent) {
                // Create new parent
                $parent = new SchoolParent();
                $parent->school_id = $student->school_id; // Explicitly set from student
                $parent->name = $student->parent_name ?? 'Parent of ' . $student->name;
                $parent->phone = $student->parent_phone;
                // Default password is phone number
                $parent->password = Hash::make($student->parent_phone);
                $parent->save();
                $this->info("Created parent: {$parent->name} ({$parent->phone}) for School {$student->school_id}");
            }

            // Link student
            $student->parent_id = $parent->id;
            $student->save();
            $count++;
        }

        $this->info("Migration completed. Linked {$count} students.");
    }
}

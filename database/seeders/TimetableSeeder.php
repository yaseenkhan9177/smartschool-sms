<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;

class TimetableSeeder extends Seeder
{
    public function run()
    {
        // Create Classes
        $classes = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];
        foreach ($classes as $class) {
            SchoolClass::firstOrCreate(['name' => $class]);
        }

        // Create Subjects
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH101'],
            ['name' => 'Science', 'code' => 'SCI101'],
            ['name' => 'English', 'code' => 'ENG101'],
            ['name' => 'History', 'code' => 'HIS101'],
            ['name' => 'Geography', 'code' => 'GEO101'],
        ];
        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['code' => $subject['code']], $subject);
        }

        // Create Dummy Teachers if none exist
        if (Teacher::count() == 0) {
            Teacher::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password'),
                'subject' => 'Mathematics',
            ]);
            Teacher::create([
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => bcrypt('password'),
                'subject' => 'Science',
            ]);
        }
    }
}

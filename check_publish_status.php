<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = App\Models\Student::where('email', 'yaseen@gmail.com')->first();
if (!$student) {
    echo "Student 'yaseen@gmail.com' not found for testing.\n";
    exit;
}

$activeTerm = App\Models\ExamTerm::where('is_active', true)->first();
if (!$activeTerm) {
    echo "No active term found.\n";
    exit;
}

echo "Checking schedules for Class ID: " . $student->class_id . " in Term: " . $activeTerm->name . "\n";

$schedules = App\Models\ExamSchedule::where('class_id', $student->class_id)
    ->where('term_id', $activeTerm->id)
    ->get();

if ($schedules->isEmpty()) {
    echo "No schedules found for this class and term.\n";
} else {
    foreach ($schedules as $s) {
        echo "Subject: " . ($s->subject->name ?? 'N/A') .
            " | is_published: " . ($s->is_published ? 'YES' : 'NO') .
            " | publish_status: " . $s->publish_status . "\n";
    }
}

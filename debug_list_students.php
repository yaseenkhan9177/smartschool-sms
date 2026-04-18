<?php

use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$students = Student::all();

echo "Total Students: " . $students->count() . "\n";
echo str_pad("ID", 5) . str_pad("Name", 30) . str_pad("Fee Count", 10) . "\n";
echo str_repeat("-", 45) . "\n";

foreach ($students as $student) {
    $count = StudentFee::where('student_id', $student->id)->count();
    echo str_pad($student->id, 5) . str_pad($student->name ?? ($student->first_name . ' ' . $student->last_name), 30) . str_pad($count, 10) . "\n";
}

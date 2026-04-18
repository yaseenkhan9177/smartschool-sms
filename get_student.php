<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = App\Models\Student::first();
if ($student) {
    echo "Student Email: " . $student->email . "\n";
    echo "Student Name: " . $student->name . "\n";
} else {
    echo "No Students Found in Database\n";
}

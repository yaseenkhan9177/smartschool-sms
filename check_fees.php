<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = App\Models\Student::where('email', 'yaseen@gmail.com')->first();
if ($student) {
    echo "Pending Balance: " . $student->currentFeeBalance() . "\n";
} else {
    echo "Student not found.\n";
}

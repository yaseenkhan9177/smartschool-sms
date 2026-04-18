<?php

use App\Models\Student;
use App\Models\StudentFee;
use App\Models\FeePayment;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Fetch all students
$students = Student::all();

echo "Starting seed for " . $students->count() . " students...\n";

foreach ($students as $student) {
    $studentId = $student->id;
    echo "Seeding fees for Student ID: {$studentId} ({$student->name})\n";

    // Clear existing fees for clean slate
    FeePayment::whereHas('studentFee', function ($q) use ($studentId) {
        $q->where('student_id', $studentId);
    })->delete();
    StudentFee::where('student_id', $studentId)->delete();

    // 1. Partial Payment Invoice
    // Invoice No: INV-202602-{ID}
    $inv1 = "INV-202602-{$studentId}";
    $fee1 = StudentFee::create([
        'student_id' => $studentId,
        'fee_structure_id' => 11, // Assumed valid
        'month' => '2026-02-01',
        'due_date' => '2026-02-10',
        'amount' => 4000.00,
        'late_fee' => 0.00,
        'discount' => 0.00,
        'status' => 'partial',
        'invoice_no' => $inv1,
    ]);

    // Payment for Inv 1
    FeePayment::create([
        'student_fee_id' => $fee1->id,
        'amount_paid' => 2000.00, // Partial
        'payment_date' => '2026-02-05',
        'payment_method' => 'online',
        'remarks' => 'Partial payment via Portal'
    ]);

    // 2. Fully Paid Invoice (Old)
    $inv2 = "INV-202601-{$studentId}";
    $fee2 = StudentFee::create([
        'student_id' => $studentId,
        'fee_structure_id' => 11,
        'month' => '2026-01-01',
        'due_date' => '2026-01-10',
        'amount' => 4000.00,
        'late_fee' => 0.00,
        'discount' => 0.00,
        'status' => 'paid',
        'invoice_no' => $inv2,
    ]);

    // Payment for Inv 2
    FeePayment::create([
        'student_fee_id' => $fee2->id,
        'amount_paid' => 4000.00,
        'payment_date' => '2026-01-08',
        'payment_method' => 'cash',
        'remarks' => 'Paid in full'
    ]);

    // 3. Paid Large Invoice
    $inv3 = "INV-202603-{$studentId}";
    $fee3 = StudentFee::create([
        'student_id' => $studentId,
        'fee_structure_id' => 11,
        'month' => '2026-03-01',
        'due_date' => '2026-03-10',
        'amount' => 16000.00,
        'late_fee' => 0.00,
        'discount' => 0.00,
        'status' => 'paid',
        'invoice_no' => $inv3,
    ]);

    FeePayment::create([
        'student_fee_id' => $fee3->id,
        'amount_paid' => 16000.00,
        'payment_date' => '2026-03-05',
        'payment_method' => 'cheque',
        'remarks' => 'Term fee cleared'
    ]);
}
echo "Seeding Completed for all students.\n";

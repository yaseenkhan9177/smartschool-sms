<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$term = App\Models\ExamTerm::where('is_active', true)->first();
if ($term) {
    echo "Active Term: " . $term->name . "\n";
} else {
    echo "No Active Term Found\n";
}

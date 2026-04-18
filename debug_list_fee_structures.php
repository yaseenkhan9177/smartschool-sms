<?php

use App\Models\FeeStructure;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$structures = FeeStructure::all();
echo "Available Fee Structures:\n";
foreach ($structures as $s) {
    echo "ID: " . $s->id . " | Class ID: " . $s->school_class_id . " | Amount: " . $s->amount . "\n";
}

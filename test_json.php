<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$months = collect(range(11, 0))->map(function ($i) {
    return \Carbon\Carbon::today()->subMonths($i)->format('M');
});

echo "\n\nJSON MAP: " . json_encode($months) . "\n\n";
echo "JSON VALUES: " . json_encode($months->values()) . "\n\n";

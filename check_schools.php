<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Table: schools\n";
$columns = Illuminate\Support\Facades\Schema::getColumnListing('schools');
foreach ($columns as $col) {
    echo "- $col\n";
}

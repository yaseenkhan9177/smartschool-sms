<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    echo "\nTable: $tableName\n";
    $columns = Illuminate\Support\Facades\Schema::getColumnListing($tableName);
    foreach ($columns as $col) {
        echo "- $col\n";
    }
}

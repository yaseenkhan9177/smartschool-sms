<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    if (Illuminate\Support\Facades\Schema::hasColumn($tableName, 'slug')) {
        echo "Found 'slug' in table: $tableName\n";
    }
    if (Illuminate\Support\Facades\Schema::hasColumn($tableName, 'database_name')) {
        echo "Found 'database_name' in table: $tableName\n";
    }
}

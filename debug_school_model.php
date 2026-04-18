<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function dumpCols($table)
{
    echo "\nTable: $table\n";
    $cols = Illuminate\Support\Facades\Schema::getColumnListing($table);
    foreach ($cols as $c) echo "- $c\n";
}

dumpCols('schools');
dumpCols('users');

$schoolCount = \App\Models\School::count();
echo "\nSchool Count: $schoolCount\n";
if ($schoolCount > 0) {
    $first = \App\Models\School::first();
    echo "First School Data:\n";
    print_r($first->toArray());
}

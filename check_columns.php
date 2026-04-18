<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function checkCols($connection)
{
    echo "\nConnection: $connection\n";
    $columns = Illuminate\Support\Facades\Schema::connection($connection)->getColumnListing('student_fees');
    foreach ($columns as $col) {
        echo "- $col\n";
    }
}

checkCols('mysql'); // Central

// Try to find a valid tenant from User or School
$school = \App\Models\School::whereNotNull('database_name')->where('status', 'active')->first();
$user = \App\Models\User::whereNotNull('database_name')->where('status', 'active')->first();

$dbName = $school ? $school->database_name : ($user ? $user->database_name : null);

if ($dbName) {
    echo "\nTesting with database: $dbName\n";
    $service = new \App\Services\TenantService();
    $service->configureConnection($dbName);
    checkCols('tenant');
} else {
    echo "\nNo active school or user with a database_name found.\n";
}

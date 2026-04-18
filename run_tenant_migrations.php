<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

$users = DB::table('users')->whereNotNull('database_name')->where('status', 'active')->get();
$databases = collect($users)->pluck('database_name')->unique();

$service = new \App\Services\TenantService();

foreach ($databases as $db) {
    echo "Migrating {$db}...\n";

    $exists = DB::connection('mysql')->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$db]);
    if (empty($exists)) {
        echo "Database {$db} does not exist. Skipping.\n";
        continue;
    }

    $service->configureConnection($db);

    try {
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);
        echo Artisan::output();
        echo "Successfully migrated {$db}.\n";
    } catch (\Exception $e) {
        echo "Failed to migrate {$db}: " . $e->getMessage() . "\n";
    }
}
echo "Done!\n";

<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Exception;

class TenantService
{
    public function configureConnection(string $databaseName): void
    {
        $config = Config::get('database.connections.mysql');
        $config['database'] = $databaseName;

        Config::set('database.connections.tenant', $config);

        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    public function createTenantDatabase(string $databaseName): void
    {
        // Check if database already exists (Improvement #3)
        $existingDatabases = array_map(function ($db) {
            return $db->Database;
        }, DB::connection('mysql')->select('SHOW DATABASES'));

        if (in_array($databaseName, $existingDatabases)) {
            throw new Exception("Database '{$databaseName}' already exists.");
        }

        DB::connection('mysql')->statement("CREATE DATABASE IF NOT EXISTS `" . $databaseName . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    public function dropTenantDatabase(string $databaseName): void
    {
        DB::connection('mysql')->statement("DROP DATABASE IF EXISTS `" . $databaseName . "`");
    }

    public function migrateAndSeedTenant(string $databaseName, $adminName, $adminEmail, $adminPassword): void
    {
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        // Pass dynamic params via config for the seeder
        config(['tenant_seed.admin_name' => $adminName]);
        config(['tenant_seed.admin_email' => $adminEmail]);
        config(['tenant_seed.admin_password' => $adminPassword]);

        Artisan::call('db:seed', [
            '--database' => 'tenant',
            '--class' => 'Database\Seeders\TenantDatabaseSeeder',
            '--force' => true,
        ]);
    }
}

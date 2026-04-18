<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        $adminExists = DB::table('super_admins')->where('email', 'superadmin@saas.com')->exists();

        if (!$adminExists) {
            DB::table('super_admins')->insert([
                'name' => 'SaaS Owner',
                'email' => 'superadmin@saas.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "Super Admin user created successfully in super_admins table!\n";
            echo "Email: superadmin@saas.com\n";
            echo "Password: password\n";
        } else {
            // Update existing super admin
            DB::table('super_admins')
                ->where('email', 'superadmin@saas.com')
                ->update([
                    'password' => Hash::make('password'),
                    'updated_at' => now(),
                ]);

            echo "Super Admin updated successfully!\n";
        }
    }
}

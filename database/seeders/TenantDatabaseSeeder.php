<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TenantDatabaseSeeder extends Seeder
{
    public function run()
    {
        $connection = DB::connection('tenant');

        $connection->table('roles')->insert([
            ['name' => 'Admin', 'slug' => 'admin', 'created_at' => Carbon::now()],
            ['name' => 'Teacher', 'slug' => 'teacher', 'created_at' => Carbon::now()],
            ['name' => 'Student', 'slug' => 'student', 'created_at' => Carbon::now()],
            ['name' => 'Parent', 'slug' => 'parent', 'created_at' => Carbon::now()],
        ]);

        $adminRoleId = $connection->table('roles')->where('slug', 'admin')->value('id');

        $adminName = config('tenant_seed.admin_name', 'School Admin');
        $adminEmail = config('tenant_seed.admin_email');
        $plainPassword = config('tenant_seed.admin_password', 'password123'); // Fallback just in case

        $connection->table('admins')->insert([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => Hash::make($plainPassword), // Improvement #6 
            'role_id' => $adminRoleId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

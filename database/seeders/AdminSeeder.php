<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $adminExists = DB::table('users')->where('email', 'admin@gmail.com')->exists();

        if (!$adminExists) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "Admin user created successfully!\n";
            echo "Email: admin@gmail.com\n";
            echo "Password: password\n";
        } else {
            // Update existing admin password
            DB::table('users')
                ->where('email', 'admin@gmail.com')
                ->update([
                    'password' => Hash::make('password'),
                    'updated_at' => now(),
                ]);

            echo "Admin password updated successfully!\n";
            echo "Email: admin@gmail.com\n";
            echo "Password: password\n";
        }
    }
}

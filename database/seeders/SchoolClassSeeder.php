<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            'Play Group',
            'Nursery',
            'Kg',
            '1st Class',
            '2nd Class',
            '3rd Class',
            '4th Class',
            '5th Class',
            '6th Class',
            '7th Class',
            '8th Class',
            '9th Class',
            '10th Class',
            '11th Class',
            '12th Class',
        ];

        foreach ($classes as $className) {
            SchoolClass::firstOrCreate(['name' => $className]);
        }
    }
}

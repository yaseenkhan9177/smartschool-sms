<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeeCategory;

class FeeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Admission Fee',
                'description' => 'One-time fee for new student admission',
            ],
            [
                'name' => 'Monthly Fee',
                'description' => 'Regular monthly tuition fee',
            ],
        ];

        foreach ($categories as $category) {
            FeeCategory::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}

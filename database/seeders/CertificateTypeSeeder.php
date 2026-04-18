<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CertificateType;

class CertificateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'School Leaving Certificate',
            'Character Certificate',
            'Bonafide Certificate',
            'Provisional Certificate',
            'Sports Achievement Certificate'
        ];

        foreach ($types as $type) {
            CertificateType::firstOrCreate(['name' => $type]);
        }
    }
}

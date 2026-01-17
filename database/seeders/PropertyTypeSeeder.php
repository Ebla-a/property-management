<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Villa',
            'Land',
            'Commercial Shop',
            'Residential Apartment',
        ];

        foreach ($types as $type) {
            PropertyType::firstOrCreate(['name' => $type]);
        }
    }
}

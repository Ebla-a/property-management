<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            'Furnished',
            'Unfurnished',
            'Garden',
            'Elevator',
            'Swimming Pool',
            'Parking',
        ];

        foreach ($amenities as $name) {
            Amenity::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}

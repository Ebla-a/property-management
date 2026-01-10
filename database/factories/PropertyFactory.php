<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->streetName . ' Apartment',
            'city' => $this->faker->city,
            'address' => $this->faker->address,
            'price' => $this->faker->numberBetween(500, 3000),

            // status used in reports
            'status' => $this->faker->randomElement([
                'available',
                'booked',
                'rented',
                'hidden',
            ]),

            // optional rating fields
            'rating_avg' => 0,
            'rating_sum' => 0,
            'rating_count' => 0,

            // timestamps auto
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Property;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // get the  employee
        $employee = User::where('email', 'employee@test.com')->firstOrFail();

        // get all properties
        $properties = Property::all();

        // ensure there are  properties
        if ($properties->isEmpty()) {
            $this->command->error('No properties found. Run PropertySeeder first.');
            return;
        }

        //  create factory
        Booking::factory()
            ->count(40)
            ->make()
            ->each(function ($booking) use ($employee, $properties) {

                $booking->employee_id = $employee->id;
                $booking->property_id = $properties->random()->id;

                $booking->save();
            });
    }
}
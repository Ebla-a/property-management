<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Reload roles from the database, clear the cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create roles if not exist
        Role::firstOrCreate(['name' => 'employee']);
        Role::firstOrCreate(['name' => 'customer']);

        // create employees
        $employees = User::factory()->count(5)->create();
        $employees->each(fn($u) => $u->assignRole('employee'));

        // create customers
        $customers = User::factory()->count(10)->create();
        $customers->each(fn($u) => $u->assignRole('customer'));

        // create properties
        // $properties = Property::factory()->count(8)->create();
        // create a default property (temporary)
        $property = Property::create([
            'title' => 'Test Property',
            'city' => 'Demo City',
            'address' => 'Sample Address',
            'price' => 1200,
        ]);



        // create bookings linked to it
        Booking::factory()->count(40)->create([
            'property_id' => $property->id
        ]);

        // create bookings
        Booking::factory()->count(40)->make()->each(function ($booking) use ($customers) {
            $booking->user_id = $customers->random()->id;
            $booking->status = fake()->randomElement([
                'pending',
                'approved',
                'rejected',
                'canceled',
                'completed'
            ]);
            $booking->save();
        });
    }
}

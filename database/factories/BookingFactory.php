<?php
namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        $date = Carbon::now()
            ->addDays(rand(1, 10))
            ->setTime(rand(9, 18), 0);

        return [
    'user_id' => User::role('customer')->inRandomOrder()->first()?->id,
    'employee_id' => null,
    'property_id' => null,
    'scheduled_at' => $date,
    'status' => $this->faker->randomElement([
        'pending',
        'approved',
        'rescheduled',
        'canceled',
        'completed',
    ]),
];
    
    }
}

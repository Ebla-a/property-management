<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * transcation => all or nothing
     */
    public function create(array $data): Booking
    {
        // Check if the property exists before using it
        $property = Property::find($data['property_id'] ?? null);
        if (!$property) {
            throw new \Exception('Property not found');
        }

        // Ensure the user is authenticated
        $userId = auth('sanctum')->id();
        if (!$userId) {
            throw new \Exception('Unauthenticated');
        }

        // Create the booking inside a transaction
        return DB::transaction(function () use ($data, $userId, $property) {

            $booking = Booking::create([
                'property_id'  => $property->id,
                'user_id'      => $userId,
                'scheduled_at' => $data['scheduled_at'],
                'status'       => 'pending',
                // Keep other fields from $data if needed
            ]);

            // Load property and employee relationships before returning
            return $booking->load(['property', 'employee']);
        });
    }
     /**
      * derails of booking
      * @param Booking $booking
      * @return Booking
      */
     public function show(Booking $booking)
    {
        return $booking->load([
            'property',
            'employee',
            'customer',
        ]);
    }
    /**
     * cancel booking
     */

    public function cancel(Booking $booking)
    {
        $booking->update([
            'status' => 'canceled',
        ]);

        return $booking;
    }
}

<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * transcation => all or nothing 
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            // prevent same property + same time booking
            $exists = Booking::where('property_id', $data['property_id'])
                ->where('scheduled_at', $data['scheduled_at'])
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                throw new \Exception('This appointment is already booked for this property');
            }

            // auto assign employee (if available)
            $employee = User::role('employee')
                ->whereDoesntHave('assignedBookings', function ($q) use ($data) {
                    $q->where('scheduled_at', $data['scheduled_at'])
                      ->whereIn('status', ['pending','approved']);
                })
                ->withCount('assignedBookings')
                ->orderBy('assigned_bookings_count')
                ->first();

            return Booking::create([
                'user_id'      => auth('sanctum')->id(), 
                'property_id'  => $data['property_id'],
                'scheduled_at' => $data['scheduled_at'],
                'status'       => 'pending',
                'employee_id'  => $employee?->id,
                'notes'        => $data['notes'] ?? null,
            ]);
        });
    }
     public function show(Booking $booking)
    {
        return $booking->load([
            'property',
            'employee',
            'customer',
        ]);
    }

    public function cancel(Booking $booking)
    {
        $booking->update([
            'status' => 'cancelled',
        ]);

        return $booking;
    }
}

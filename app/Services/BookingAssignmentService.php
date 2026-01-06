<?php
namespace App\Services;

use App\Models\User;


class BookingAssignmentService
{
    public function assignAvailableEmployee($scheduledAt)
    {
        return User::role('employee')
            ->withCount([
                'assignedBookings as bookings_at_same_time' => function ($query) use ($scheduledAt) {
                    $query->whereBetween('scheduled_at', [
                        now()->parse($scheduledAt)->subHour(),
                        now()->parse($scheduledAt)->addHour()
                    ]);
                }
            ])
            ->having('bookings_at_same_time', '=', 0)
            ->orderBy('bookings_count')
            ->first();
    }
}

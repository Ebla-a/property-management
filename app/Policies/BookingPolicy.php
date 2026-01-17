<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookingPolicy
{
    /**
     * admin see every thing
     * emloyee sees his booking
     * and bookings that not assigned to any employee
     */
    public function viewAny(User $user): bool
    {
        return   $user->hasRole('admin');
    }

    /**
     * View booking
     */
    public function view(User $user, Booking $booking): bool
    {
        return
         // admin can view all
            $user->hasRole('admin')||

            // customer can view his booking
            $booking->user_id === $user->id ||

            // assigned employee can view
            $user->hasRole('employee') && (
                $booking->employee_id == $user->id || is_null($booking->employee_id));
    }


    /**
     * Customer cancels booking only if pending
     */
    public function cancel(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('customer') &&
            $booking->user_id === $user->id &&
            $booking->status === 'pending';
    }


    /**
     * Employee approves booking
     */
public function approve(User $user, Booking $booking): bool
{
    // employee can approve if:
    // 1. assigned to him OR 2. not assigned yet
    $canAssign = $user->hasRole('employee') && ($booking->employee_id == $user->id || is_null($booking->employee_id));

    return $canAssign && in_array($booking->status, ['pending', 'rescheduled']);
}

public function employeeCancel(User $user, Booking $booking): bool
{
    // employee can cancel if:
    // 1. assigned to him OR 2. not assigned yet
    $canAssign = $user->hasRole('employee') && ($booking->employee_id == $user->id || is_null($booking->employee_id));

    return $canAssign && in_array($booking->status, ['pending', 'approved', 'rescheduled']);
}

public function reschedule(User $user, Booking $booking): bool
{
    $canAssign = $user->hasRole('employee') && ($booking->employee_id == $user->id || is_null($booking->employee_id));

    return $canAssign && in_array($booking->status, ['pending', 'approved', 'rescheduled']);
}

public function complete(User $user, Booking $booking): bool
{
    $canAssign = $user->hasRole('employee') && ($booking->employee_id == $user->id || is_null($booking->employee_id));

    return $canAssign && $booking->status === 'approved';
}

    public function reject(User $user, Booking $booking)
    {
        return
            $user->hasRole('employee') || is_null($booking->employee_id) &&
            $booking->employee_id === $user->id &&
            $booking->status === 'pending';
    }
}

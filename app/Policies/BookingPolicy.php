<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * View booking
     */
    public function view(User $user, Booking $booking): bool
    {
        return

            // customer can view his booking
            $booking->user_id === $user->id ||

            // assigned employee can view
            ($user->hasRole('employee')
             && $booking->employee_id === $user->id) ||

            // admin can view all
            $user->hasRole('admin');
    }


    /**
     * Customer cancels booking only if pending
     */
    public function cancel(User $user, Booking $booking): bool
    {
        return
            $booking->user_id === $user->id &&
            $booking->status === 'pending';
    }


    /**
     * Employee approves booking
     */
    public function approve(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            $booking->status === 'pending';
    }


    /**
     * Employee cancels booking
     */
    public function employeeCancel(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            in_array($booking->status , ['pending','approved']);
    }


    /**
     *  Reschedule — Employee only
     */
    public function reschedule(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            in_array($booking->status , ['pending','approved']);
    }


    /**
     * Complete booking — employee only
     */
    public function complete(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            $booking->status === 'approved';
    }
}

<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine if the user can view any bookings.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if the user can view a specific booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('admin') ||
            $booking->user_id === $user->id ||
            ($user->hasRole('employee') && ($booking->employee_id == $user->id || is_null($booking->employee_id)));
    }

    /**
     * Customer cancels booking only if pending.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('customer') &&
            $booking->user_id === $user->id &&
            $booking->status === 'pending';
    }

    /**
     * Employee approves booking.
     */
    public function approve(User $user, Booking $booking): bool
    {
        $isAuthorized = $user->hasRole('employee') && ($booking->employee_id == $user->id || is_null($booking->employee_id));
        return $isAuthorized && in_array($booking->status, ['pending', 'rescheduled']);
    }

    /**
     * Employee cancels booking.
     */
    public function employeeCancel(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            in_array($booking->status, ['pending', 'approved', 'rescheduled']);
    }

    /**
     * Reschedule — Employee only.
     */
    public function reschedule(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id  &&
            in_array($booking->status, ['pending', 'approved', 'rescheduled']);
    }

    /**
     * Complete booking — employee only.
     */
    public function complete(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            $booking->status === 'approved';
    }

    /**
     * Reject booking.
     */
    public function reject(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            $booking->status === 'pending';
    }
}
<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EmployeeBookingService
{
    /**
     * Get bookings for an employee — include unassigned pending bookings
     * so employee can see new API bookings as well.
     */
    public function getEmployeeBooking($employeeId)
    {
        return Booking::with(['user', 'property'])
            ->where(function ($q) use ($employeeId) {
                $q->where('employee_id', $employeeId)
                    ->orWhere(function ($q2) {
                        $q2->whereNull('employee_id')
                            ->where('status', 'pending');
                    });
            })
            ->latest()
            ->paginate(10);
    }

    /**
     * Approve booking — assign employee if unassigned, check time conflict.
     */
    public function approve(Booking $booking)
    {
        if (! in_array($booking->status, ['pending', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed. Booking must be pending or rescheduled.',
            ]);
        }

        // Assign current employee if unassigned
        if (is_null($booking->employee_id)) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        // Time conflict check for the (now assigned) employee
        if ($this->hasTimeConflict(
            $booking->employee_id,
            $booking->scheduled_at,
            $booking->id
        )) {
            throw ValidationException::withMessages([
                'scheduled_at' => 'Employee has another booking at this time',
            ]);
        }

        $booking->update([
            'status' => 'approved',
            'rescheduled_at' => null,
        ]);

        return $booking;
    }

    /**
     * Cancel booking — assign employee if unassigned, then cancel
     */
    public function cancel(Booking $booking)
    {
        if (! in_array($booking->status, ['pending', 'approved', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed',
            ]);
        }

        // Assign current employee if unassigned (so employee "takes" the booking before cancelling)
        if (is_null($booking->employee_id)) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        $booking->update([
            'status' => 'canceled',
        ]);

        return $booking;
    }

    /**
     * Reschedule booking — assign employee if unassigned, check time conflict
     */
    public function reschedule(Booking $booking, $scheduleAt)
    {
        // Assign if unassigned
        if (is_null($booking->employee_id)) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        if ($this->hasTimeConflict(
            $booking->employee_id,
            $scheduleAt,
            $booking->id
        )) {
            throw ValidationException::withMessages([
                'scheduled_at' => 'Employee already has a booking at this time',
            ]);
        }

        if (! in_array($booking->status, ['pending', 'approved', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed',
            ]);
        }

        $booking->update([
            'status' => 'rescheduled',
            'scheduled_at' => $scheduleAt,
            'rescheduled_at' => now(),
        ]);

        return $booking;
    }

    /**
     * Complete booking — assign if unassigned, require approved status
     */
    public function complete(Booking $booking)
    {
        // Assign if unassigned
        if (is_null($booking->employee_id)) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        if ($booking->employee_id !== Auth::id()) {
            abort(403, 'Forbidden'); // authorization check
        }

        if (! in_array($booking->status, ['approved', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed',
            ]);
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return $booking;
    }

    /**
     * Check if employee already has a booking in +/-1 hour range
     */
    public function hasTimeConflict($employeeId, $scheduledAt, $excludeId = null)
    {
        if (empty($employeeId)) {
            return false;
        }

        return Booking::where('employee_id', $employeeId)
            ->when(
                $excludeId,
                fn ($q) => $q->where('id', '!=', $excludeId)
            )
            ->whereBetween('scheduled_at', [
                Carbon::parse($scheduledAt)->subHour(),
                Carbon::parse($scheduledAt)->addHour(),
            ])
            ->exists();
    }

    /**
     * Reject booking — assign if unassigned, allow only pending
     */
    public function reject(Booking $booking, $reason = null)
    {
        // Assign if unassigned
       if (is_null($booking->employee_id)) {
        $booking->employee_id = Auth::id();
        $booking->save();
    }

        if ($booking->employee_id !== Auth::id()) {
        abort(403, 'You are not allowed to reject this booking');
    }

       if (! in_array($booking->status, ['pending', 'rescheduled'])) {
        throw ValidationException::withMessages([
            'status' => 'Action is not allowed. Booking must be pending or rescheduled.',
        ]);
        }

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);

        return $booking;
    }
}

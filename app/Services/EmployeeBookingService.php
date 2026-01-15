<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeBookingService
{
    public function getEmployeeBooking($employeeId)
    {
        return Booking::where('employee_id', $employeeId)
            ->latest()
            ->paginate(10);
    }

  

    public function approve(Booking $booking)
    {
        
        if (!in_array($booking->status, ['pending', 'rescheduled'])) {
            abort(422, 'Action not allowed. Booking must be pending or rescheduled.');
        }

        if ($this->hasTimeConflict(
            $booking->employee_id,
            $booking->scheduled_at,
            $booking->id
        )) {
            abort(422, 'Employee has another booking at this time');
        }

        $booking->update([
            'status' => 'approved',
            'rescheduled_at' => null

        ]);

        return $booking;
    }

    public function cancel(Booking $booking)
    {

        if (! in_array($booking->status, ['pending', 'approved'])) {
            abort(422, 'Action not allowed');
        }

        $booking->update([
            'status' => 'canceled'
        ]);

        return $booking;
    }

    public function reschedule(Booking $booking, $scheduleAt)
    {

        if ($this->hasTimeConflict(
            $booking->employee_id,
            $scheduleAt,
            $booking->id
        )) {
            abort(422, 'Employee already has booking at this time');
        }


        if (! in_array($booking->status, ['pending', 'approved'])) {
            abort(422, 'Action not allowed');
        }

        $booking->update([
            'status' => 'rescheduled',
            'scheduled_at' => $scheduleAt,
            'rescheduled_at' => now()
        ]);

        return $booking;
    }
    /**
     * complete booking
     */

    public function complete(Booking $booking)
    {

        if ($booking->employee_id !== Auth::id()) {
            abort(403, 'Forbidden');
        }

        if (!in_array($booking->status, ['approved', 'rescheduled'])) {
            abort(422, 'Action not allowed');
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return $booking;
    }
    /**
     * Carbon is a library for handling time and dates
     */
    public function hasTimeConflict($employeeId, $scheduledAt, $excludeId = null)
    {
        if (empty($employeeId)) {
            return false;
        }

        return Booking::where('employee_id', $employeeId)
            ->when(
                $excludeId,
                fn($q) =>
                $q->where('id', '!=', $excludeId)
            )
            ->whereBetween('scheduled_at', [
                Carbon::parse($scheduledAt)->subHour(),
                Carbon::parse($scheduledAt)->addHour(),
            ])
            ->exists();
    }
    /**
     * reject a booking
     */

    public function reject(Booking $booking, $reason = null)
    {
        if ($booking->employee_id !== Auth::id()) {
            abort(403, 'You are not allowed to reject this booking');
        }
        // reject only if status pending
        if ($booking->status !== 'pending') {
            abort(422, 'action is not allowed');
        }

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);
        return $booking;
    }
}

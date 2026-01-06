<?php 
namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class EmployeeBookingService
{
    public function getEmployeeBooking($employeeId)
    {
        return Booking::where('employee_id', $employeeId)
            ->latest()
            ->paginate(10);
    }

    public function getBookingDetails($id)
    {
        return Booking::findOrFail($id);
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        if ($this->hasTimeConflict(
            $booking->employee_id,
            $booking->scheduled_at,
            $booking->id
        )) {
            abort(422, 'Employee has another booking at this time');
        }

        if ($booking->status !== 'pending') {
            abort(422, 'Action not allowed');
        }

        $booking->update([
            'status' => 'approved'
        ]);

        return $booking;
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if (! in_array($booking->status, ['pending','approved'])) {
            abort(422, 'Action not allowed');
        }

        $booking->update([
            'status' => 'canceled'
        ]);

        return $booking;
    }

    public function reschedule($id, $scheduleAt)
    {
        $booking = Booking::findOrFail($id);

        if ($this->hasTimeConflict(
            $booking->employee_id,
            $scheduleAt,
            $booking->id
        )) {
            abort(422, 'Employee already has booking at this time');
        }

        if (! in_array($booking->status, ['pending','approved'])) {
            abort(422, 'Action not allowed');
        }

        $booking->update([
            'status' => 'rescheduled',
            'scheduled_at' => $scheduleAt
        ]);

        return $booking;
    }

    public function complete($id)
    {
        $booking = Booking::findOrFail($id);
         
        if ($booking->employee_id !== auth('sanctum')->id()) {
        abort(403, 'Forbidden');
        }

        if ( !in_array($booking->status, ['approved','rescheduled'])) {
            abort(422,'Action not allowed');
        }

        $booking->update([
            'status' =>'completed',
            'completed_at' => now()
        ]);

        return $booking;
    }
    /**
     * Carbon is a library for handling time and dates
     */
    public function hasTimeConflict($employeeId, $scheduledAt, $excludeId = null)
    {
        return Booking::where('employee_id', $employeeId)
            ->when($excludeId, fn($q) =>
                $q->where('id', '!=', $excludeId)
            )
            ->whereBetween('scheduled_at', [
                Carbon::parse($scheduledAt)->subHour(),
                Carbon::parse($scheduledAt)->addHour(),
            ])
            ->exists();
    }
   
    public function reject($id, $reason = null)
    {
        $booking = Booking::findOrFail($id );
        if($booking->employee_id !== auth('sanctum')->id())
        {
              abort(403, 'You are not allowed to reject this booking');
        }
        // reject only if status pending
        if($booking->status !== 'pending'){
            abort(422 , 'action is not allowed');
        }

        $booking->update([
            'status' =>'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);
        return $booking;
    }
}

<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectBookingRequest;
use App\Http\Requests\RescheduleBookingRequest;
use App\Models\Booking;
use App\Services\EmployeeBookingService;
use Illuminate\Support\Facades\Auth;

class EmployeeBookingController extends Controller
{
    protected EmployeeBookingService $employeeBookingService;

    public function __construct(EmployeeBookingService $employeeBookingService)
    {
        $this->employeeBookingService = $employeeBookingService;
    }

    /**
     * List bookings for logged-in employee
     */
   public function index()
{
    $user = auth()->user();

    // Admin  can see all bookings
    if ($user->hasRole('admin')) {
        $bookings = Booking::latest()->paginate(10);
    }
    // Employee  sees only his bookings
    elseif ($user->hasRole('employee')) {
        $bookings = Booking::where('assigned_to', $user->id)
                           ->latest()
                           ->paginate(10);
    }

    return view('dashboard.bookings.index', compact('bookings'));
}


    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        $booking = $this->employeeBookingService->getBookingDetails($booking);

        return view('dashboard.employee.bookings.show', [
            'booking' => $booking
        ]);
    }

    /**
     * Approve booking
     */
    public function approve(Booking $booking)
    {
        $booking = $this->employeeBookingService->approve($booking);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking approved successfully');
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking)
    {
        $booking = $this->employeeBookingService->cancel($booking);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking cancelled');
    }

    /**
     * Reschedule booking
     */
    public function reschedule(RescheduleBookingRequest $request, Booking $booking)
    {
        $booking = $this->employeeBookingService
            ->reschedule($booking, $request->scheduled_at);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking rescheduled successfully');
    }

    /**
     * Mark booking as completed
     */
    public function complete(Booking $booking)
    {
        $booking = $this->employeeBookingService->complete($booking);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking completed');
    }

    /**
     * Reject booking with reason
     */
    public function reject(RejectBookingRequest $request, Booking $booking)
    {
        $booking = $this->employeeBookingService->reject(
            $booking,
            $request->reason
        );

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking rejected');
    }
}

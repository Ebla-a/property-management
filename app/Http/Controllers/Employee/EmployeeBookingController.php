<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectBookingRequest;
use App\Http\Requests\RescheduleBookingRequest;
use App\Services\EmployeeBookingService;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $bookings = $this->employeeBookingService
            ->getEmployeeBooking($request->user()->id);

        return view('dashboard.employee.bookings.index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Show booking details
     */
    public function show($id)
    {
        $booking = $this->employeeBookingService->getBookingDetails($id);

        return view('dashboard.employee.bookings.show', [
            'booking' => $booking
        ]);
    }

    /**
     * Approve booking
     */
    public function approve($id)
    {
        $booking = $this->employeeBookingService->approve($id);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking approved successfully');
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        $booking = $this->employeeBookingService->cancel($id);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking cancelled');
    }

    /**
     * Reschedule booking
     */
    public function reschedule(RescheduleBookingRequest $request, $id)
    {
        $booking = $this->employeeBookingService
            ->reschedule($id, $request->scheduled_at);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking rescheduled successfully');
    }

    /**
     * Mark booking as completed
     */
    public function complete($id)
    {
        $booking = $this->employeeBookingService->complete($id);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking completed');
    }

    /**
     * Reject booking with reason
     */
    public function reject(RejectBookingRequest $request, $id)
    {
        $booking = $this->employeeBookingService->reject(
            $id,
            $request->reason
        );

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking rejected');
    }
}

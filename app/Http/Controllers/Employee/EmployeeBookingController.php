<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectBookingRequest;
use App\Http\Requests\RescheduleBookingRequest;
use App\Models\Booking;
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
     * List bookings for logged-in employee or admin
     *
     * Supports status filtering:
     * - Admin   → sees all bookings
     * - Employee → sees only assigned bookings
     */
    public function index(Request $request)
    {
        $user   = auth()->user();
        $status = $request->get('status');

        // Admin
        if ($user->hasRole('admin')) {

            $bookings = Booking::with(['user','property','employee'])
                ->when($status, fn($q) => $q->where('status', $status))
                ->latest()
                ->paginate(10);
        }

        // Employee
        elseif ($user->hasRole('employee')) {

            $bookings = Booking::with(['user','property'])
                ->where('employee_id', $user->id)
                ->when($status, fn($q) => $q->where('status', $status))
                ->latest()
                ->paginate(10);
        }

        return view('dashboard.bookings.index', compact('bookings','status'));
    }


    /**
     * Show booking details
     *
     * Authorization rules:
     * - Assigned employee
     * - Customer who owns booking
     * - Admin
     */
    public function show($id)
    {
        $booking = $this->employeeBookingService->getBookingDetails($id);

        $this->authorize('view', $booking);

        return view('dashboard.bookings.show', compact('booking'));
    }


    /**
     * Approve booking (employee only)
     *
     * Allowed when:
     * - user has role employee
     * - is assigned to booking
     * - booking is pending
     */
    public function approve($id)
    {
        $booking = $this->employeeBookingService->getBookingDetails($id);

        $this->authorize('approve', $booking);

        $booking = $this->employeeBookingService->approve($id);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking approved successfully');
    }


    /**
     * Cancel booking (employee allowed for pending/approved)
     */
    public function cancel($id)
    {
        $booking = $this->employeeBookingService->getBookingDetails($id);

        $this->authorize('employeeCancel', $booking);

        $booking = $this->employeeBookingService->cancel($id);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking cancelled');
    }


    /**
     * Reschedule booking (employee)
     */
    public function reschedule(RescheduleBookingRequest $request, $id)
    {
        $booking = $this->employeeBookingService->getBookingDetails($id);

        $this->authorize('reschedule', $booking);

        $booking = $this->employeeBookingService
            ->reschedule($id, $request->scheduled_at);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking rescheduled successfully');
    }


    /**
     * Mark booking as completed (employee)
     */
    public function complete($id)
    {
        $booking = $this->employeeBookingService->getBookingDetails($id);

        $this->authorize('complete', $booking);

        $booking = $this->employeeBookingService->complete($id);

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking completed');
    }


    /**
     * Reject booking with reason (employee)
     */
    public function reject(RejectBookingRequest $request, $id)
    {
        $booking = $this->employeeBookingService->getBookingDetails($id);

        $this->authorize('approve', $booking);

        $booking = $this->employeeBookingService->reject(
            $id,
            $request->reason
        );

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', 'Booking rejected');
    }
}

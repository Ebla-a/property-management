<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectBookingRequest;
use App\Http\Requests\RescheduleBookingRequest;
use App\Models\Booking;
use App\Models\User;
use App\Services\EmployeeBookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingActionNotification;

class EmployeeBookingController extends Controller
{
    protected EmployeeBookingService $employeeBookingService;

    public function __construct(EmployeeBookingService $employeeBookingService)
    {
        $this->employeeBookingService = $employeeBookingService;
    }

    /**
     * List bookings for Admin only
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Booking::class);

        $user = $request->user();
        $status = $request->get('status');

        if ($user->hasRole('admin')) {
            $bookings = Booking::with(['user', 'property', 'employee', 'review.user'])
                ->when($status, fn ($q) => $q->where('status', $status))
                ->latest()
                ->paginate(6);

            return view('dashboard.bookings.index', compact('bookings', 'status'));
        }
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        return view('dashboard.bookings.show', compact('booking'));
    }

    /**
     * Approve booking
     */
    public function approve(Booking $booking)
    {
        try {
            $this->authorize('approve', $booking);

            if (is_null($booking->employee_id)) {
                $booking->update(['employee_id' => Auth::id()]);
            }

            $booking = $this->employeeBookingService->approve($booking);

            // Notify admins and employees (Accepted from wajd)
            $by = auth()->user() ? auth()->user()->name : 'System';
            $users = User::role(['admin', 'employee'])->get();
            foreach ($users as $user) {
                $user->notify(new BookingActionNotification('approved', $booking->id, $by));
            }

            return redirect()
                ->route('employee.bookings.show', $booking->id)
                ->with('status', __('messages.booking.approved'));
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking)
    {
        $this->authorize('employeeCancel', $booking);
        $booking = $this->employeeBookingService->cancel($booking);

        $by = auth()->user() ? auth()->user()->name : 'System';
        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new BookingActionNotification('cancelled', $booking->id, $by));
        }

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', __('messages.booking.cancelled'));
    }

    /**
     * Reschedule logic
     */
    public function reschedule(RescheduleBookingRequest $request, Booking $booking)
    {
        $this->authorize('reschedule', $booking);
        $booking = $this->employeeBookingService->reschedule($booking, $request->scheduled_at);

        $by = auth()->user() ? auth()->user()->name : 'System';
        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new BookingActionNotification('rescheduled', $booking->id, $by));
        }

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', __('messages.booking.reschedule'));
    }

    public function rescheduleForm(Booking $booking)
    {
        $this->authorize('reschedule', $booking);

        return view('dashboard.bookings.reschedule', compact('booking'));
    }

    /**
     * Mark as completed
     */
    public function complete(Booking $booking)
    {
        $this->authorize('complete', $booking);
        $booking = $this->employeeBookingService->complete($booking);

        $by = auth()->user() ? auth()->user()->name : 'System';
        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new BookingActionNotification('completed', $booking->id, $by));
        }

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', __('messages.booking.completed'));
    }

    /**
     * Reject booking
     */
    public function reject(RejectBookingRequest $request, Booking $booking)
    {
        $this->authorize('reject', $booking);
        $booking = $this->employeeBookingService->reject($booking, $request->reason);

        $by = auth()->user() ? auth()->user()->name : 'System';
        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new BookingActionNotification('rejected', $booking->id, $by));
        }

        return redirect()
            ->route('employee.bookings.show', $booking->id)
            ->with('status', __('messages.booking.rejected'));
    }

    /**
     * Employee's own bookings
     */
    public function myBookings(Request $request)
    {
        $employee = $request->user();
        $status = $request->query('status');

        $query = Booking::with(['user', 'property'])
            ->where('employee_id', $employee->id);

        if ($status && in_array($status, ['pending', 'approved', 'completed', 'rejected', 'canceled', 'rescheduled'])) {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(6);

        $counts = [
            'all' => Booking::where('employee_id', $employee->id)->count(),
            'pending' => Booking::where('employee_id', $employee->id)->where('status', 'pending')->count(),
            'approved' => Booking::where('employee_id', $employee->id)->where('status', 'approved')->count(),
            'rescheduled' => Booking::where('employee_id', $employee->id)->where('status', 'rescheduled')->count(),
            'completed' => Booking::where('employee_id', $employee->id)->where('status', 'completed')->count(),
            'rejected' => Booking::where('employee_id', $employee->id)->where('status', 'rejected')->count(),
            'canceled' => Booking::where('employee_id', $employee->id)->where('status', 'canceled')->count(),
        ];

        return view('dashboard.bookings.employeebookings', compact('bookings', 'employee', 'counts'));
    }

    /**
     * Pending bookings (unassigned)
     */
    public function pending()
    {
        $bookings = Booking::whereNull('employee_id')
            ->where('status', 'pending')
            ->latest()
            ->paginate(6);

        return view('dashboard.bookings.pending', compact('bookings'));
    }
}
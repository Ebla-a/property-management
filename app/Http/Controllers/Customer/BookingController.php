<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingActionNotification;
use App\Services\BookingService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Retrieve bookings for the authenticated customer.
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['property', 'employee'])
            ->where('user_id', auth('sanctum')->id())
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    /**
     * Submit a new booking request.
     */
    public function store(BookingRequest $request)
    {
        try {
            $booking = $this->bookingService->create($request->validated());

            // Notify Admins
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new BookingActionNotification(
                    action: 'created',
                    bookingId: $booking->id,
                    byUser: $booking->user->name ?? 'Customer'
                ));
            }

            // Notify Employees
            $employees = User::role('employee')->get();
            foreach ($employees as $employee) {
                $employee->notify(new BookingActionNotification(
                    action: 'created',
                    bookingId: $booking->id,
                    byUser: $booking->user->name ?? 'Customer'
                ));
            }

            return response()->json([
                'message' => __('messages.booking.created'),
                'data' => new BookingResource($booking),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Show details of a specific booking.
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking = $this->bookingService->show($booking);
        return new BookingResource($booking);
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        $booking = $this->bookingService->cancel($booking);
        $byUser = $booking->user->name ?? 'Customer';

        // Notify Admins
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new BookingActionNotification(
                action: 'cancelled',
                bookingId: $booking->id,
                byUser: $byUser
            ));
        }

        // Notify Employees
        $employees = User::role('employee')->get();
        foreach ($employees as $employee) {
            $employee->notify(new BookingActionNotification(
                action: 'cancelled',
                bookingId: $booking->id,
                byUser: $byUser
            ));
        }

        return response()->json([
            'message' => __('messages.booking.canceled'),
            'data' => new BookingResource($booking),
        ], 200);
    }
}
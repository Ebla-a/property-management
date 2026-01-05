<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private BookingService $bookingService) {
        $this->bookingService = $bookingService;
    }

    /**
     * Retrieve bookings for the authenticated customer.
     *
     * - Returns only bookings belonging to the logged-in user
     * - Loads relations: (property, employee)
     * - Supports status filtering via ?status=
     * - Results are paginated and wrapped in BookingResource collection
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['property','employee'])
            ->where('user_id', auth('sanctum')->id())
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    /**
     * Submit a new booking request.
     *
     * - Request validation is handled by BookingRequest
     * - Booking creation logic is delegated to BookingService
     * - Returns the created booking as a Resource
     *
     * @param BookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BookingRequest $request)
    {
        try {

            $booking = $this->bookingService->create($request->validated());

            return response()->json([
                'message' => 'The request has been sent successfully',
                'data'    => new BookingResource($booking),
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Show details of a specific booking.
     *
     * Authorization: policy -> view
     *
     * - Customer can view only their own bookings
     * - Additional permissions may apply according to policy rules
     *
     * @param Booking $booking
     * @return BookingResource
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking = $this->bookingService->show($booking);

        return new BookingResource($booking);
    }

    /**
     * Cancel a booking made by the authenticated customer.
     *
     * Authorization: policy -> cancel
     *
     * Rules enforced by policy:
     * - User can cancel only their own booking
     * - Only bookings with status = "pending" may be cancelled
     * - Cancellation logic is processed in BookingService
     *
     * @param Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        $booking = $this->bookingService->cancel($booking);

        return response()->json([
            'message' => 'Booking cancelled successfully',
            'data'    => new BookingResource($booking),
        ], 200);
    }
}

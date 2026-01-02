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
    public function __construct(private BookingService $bookingService) {}
    /**
     *  to get data organized for each element from BookingResource instead of json
     * collection()->get all bookings
     * when(condition,callback) — Conditional Query Method -> if condition true ->applay callback
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

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking = $this->bookingService->show($booking);

        return new BookingResource($booking);
    }
    /**
     *  user can cancel only his booking
     *  only pending bookings can be cancelled
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

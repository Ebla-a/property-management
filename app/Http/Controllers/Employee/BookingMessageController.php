<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingMessageRequest;
use App\Models\Booking;
use App\Services\BookingCommunicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller to manage booking-related chat messages.
 */
class BookingMessageController extends Controller
{
    protected BookingCommunicationService $service;

    /**
     * Injecting the communication service and applying authentication middlewares.
     */
    public function __construct(BookingCommunicationService $service)
    {
        $this->service = $service;
        // Ensuring only active and authenticated users access these routes
        $this->middleware(['auth', 'check.active']);
    }

    /**
     * Fetch all messages for a specific booking.
     *
     * @param  Booking  $booking  Route Model Binding for the booking
     */
    public function index(Booking $booking, Request $request): JsonResponse
    {
        $messages = $this->service->getMessages(
            $booking,
            (int) $request->user()->id
        );
        if ($messages instanceof \Illuminate\Database\Eloquent\Collection) {
        $messages->load('sender:id,name'); 
    }

        return response()->json($messages);
    }

    /**
     * Store and broadcast a new message.
     *
     * @param  Booking  $booking  Route Model Binding for the booking
     * @param  StoreBookingMessageRequest  $request  Validated request class
     */
    public function store(Booking $booking, StoreBookingMessageRequest $request): JsonResponse
    {
        // Retrieve only validated data from the Request class
        $data = $request->validated();

        $message = $this->service->sendMessage(
            $booking,
            (int) $request->user()->id,
            $data['message']
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $message->load('sender:id,name'),
        ], 201);
    }
}

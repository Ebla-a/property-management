<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingCommunicationService;
use Illuminate\Http\Request;

class BookingMessageController extends Controller
{
    protected BookingCommunicationService $service;

    public function __construct(BookingCommunicationService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:sanctum', 'check.active']);
    }

    // show convirsation
    public function index(Booking $booking, Request $request)
    {
        $messages = $this->service->getMessages(
            $booking,
            $request->user()->id
        );

        return response()->json($messages);
    }

    // send message
    public function store(Booking $booking, Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = $this->service->sendMessage(
            $booking,
            $request->user()->id,
            $request->message
        );

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message
        ], 201);
    }
}

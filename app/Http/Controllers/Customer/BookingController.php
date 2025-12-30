<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     *  to get data organized for each element from BookingResource instead of json 
     * collection()->get all bookings
     * when(condition,callback) — Conditional Query Method -> if condition true ->applay callback
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['property'])
        ->where('user_id' , auth()->id())
        ->when($request->status , function($q) use ($request)
        {
            $q->where('status' ,$request->status);
        })->latest()->paginate(10);
         return BookingResource::collection($bookings); 
    }
    public function store(BookingRequest $request , BookingService $service)
    {
        try{
            $booking = $service->create($request->validated());
            return response()->json(
            [
                'message' =>'The request has been sent successfully',
                'data'    => new BookingResource($booking),
            ],201);
        }
        catch(\Exception $e) {
            return response()->json([
                'message' =>$e->getMessage()
            ] ,422);
        }  
    }
    /**
     * add (if)=>   only the user can see his booking
     * @param Booking $booking
     * @return BookingResource|\Illuminate\Http\JsonResponse
     */
    public function show(Booking $booking)
    {
         if($booking->customer->id !== auth()->id()) {
            return response()->json([
                'message' =>'Unauthorized'
            ],403);
         }
        return new BookingResource($booking);
       

    }
    public function cancel(Booking $booking)
{
    // user can cancel only his booking
    if ($booking->user_id !== auth()->id()) {
        return response()->json([
            'message' => 'Forbidden'
        ], 403);
    }

    // only pending bookings can be cancelled
    if ($booking->status !== 'pending') {
        return response()->json([
            'message' => 'Only pending bookings can be cancelled',
        ], 422);
    }

    // update status
    $booking->update([
        'status' => 'cancelled'
    ]);

    return response()->json([
        'message' => 'Booking cancelled successfully',
        'data' => new BookingResource($booking),
    ], 200);
}


}


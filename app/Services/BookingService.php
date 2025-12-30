<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function create(array $data )
    {
        /**transaction => do it all or nothing 
         * if there booking in the same time and date  => the procces is canclled
         */
        return DB::transaction(function () use($data){
            $exists = Booking::where('property_id' ,$data['property_id'])
            ->where('scheduled_at' , $data['scheduled_at'])
            ->lockForUpdate() // prevent dublicate bookings in the same second
            ->exists();
            if($exists){
                throw new \Exception('the appointment is already taken');
            }
            $booking = Booking::create(
                [
                    'user_id' => auth()->id(),
                    'property_id' =>$data['property_id'],
                    'scheduled_at' =>$data['scheduled_at'],
                    'status' => 'pending',
                    'notes' =>$data['notes'] ?? null
                ]);
          return $booking;
        }
    );
}
}

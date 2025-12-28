<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\Rating;
use Exception;

class RatingService
{
    public function addRating(int $userId, int $bookingId, int $score, ?string $comment = null): Rating
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== $userId) {
            throw new Exception("You are not allowed to rate this reservation");
        }

        if ($booking->status !== 'completed') {
            throw new Exception("You cannot evaluate until the visit is complete");
        }

        return Rating::create([
            'booking_id' => $bookingId,
            'user_id' => $userId,
            'property_id' => $booking->property_id,
            'score' => $score,
            'comment' => $comment,
        ]);
    }
}

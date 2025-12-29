<?php
namespace App\Services;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Auth\Access\AuthorizationException;


class ReviewService
{
    public function addRating(int $userId, array $data): Review
    {
        $booking = Booking::findOrFail($data['booking_id']);


        if ($booking->user_id !== $userId) {
        throw new AuthorizationException("You are not allowed to rate this reservation");
    }

        if ($booking->status !== 'approved') {
        throw new BadRequestHttpException("You cannot evaluate until the visit is complete");
    }

       $alreadyRating = Review::where('user_id', $userId)
       ->where('property_id', $booking->property_id)
       ->exists();

       if ($alreadyRating) {
         throw new BadRequestHttpException(
        'You have already reviewed this property'
       );
    }

        return Review::create($data + [
            'booking_id' => $booking->id,
            'user_id' => $userId,
            'property_id' => $booking->property_id,
        ]);
    }
}

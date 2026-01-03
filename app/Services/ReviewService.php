<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use App\Models\Property;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReviewService
{
    public function addRating(int $userId, array $data): Review
    {
        return DB::transaction(function () use ($userId, $data) {

            $booking = Booking::findOrFail($data['booking_id']);

            if ($booking->user_id !== $userId) {
                throw new AuthorizationException(
                    "You are not allowed to rate this reservation"
                );
            }

            if ($booking->status !== 'completed') {
                throw new BadRequestHttpException(
                    "You cannot rate or leave a comment until the visit is complete"
                );
            }

            $review = Review::where('user_id', $userId)
                ->where('property_id', $booking->property_id)
                ->first();
                
            if ($review) {

                if (isset($data['rating'])) {
                    throw new BadRequestHttpException(
                        "Rating cannot be evaluated more than once and cannot be modified"
                    );
                }

                $review->update([
                    'comment' => $data['comment'],
                ]);

                return $review;
            }

            if (!isset($data['rating'])) {
                throw new BadRequestHttpException(
                    "Rating is required for the first review"
                );
            }

            $review = Review::create([
                'booking_id'  => $booking->id,
                'user_id'     => $userId,
                'property_id' => $booking->property_id,
                'rating'      => $data['rating'],
                'comment'     => $data['comment'] ?? null,
            ]);

            $property = Property::findOrFail($booking->property_id);

            $property->increment('rating_count');
            $property->increment('rating_sum', $data['rating']);

            $property->update([
                'rating_avg' => round(
                    $property->rating_sum / $property->rating_count,
                    2
                )
            ]);

            return $review;
        });
    }
    public function toggleUserStatus(int $userId, bool $status): User
    {
    $user = User::findOrFail($userId);

    $user->update(['is_active' => $status]);

    return $user;
    }
}

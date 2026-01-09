<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\BookingMessage;
use Illuminate\Auth\Access\AuthorizationException;

class BookingCommunicationService
{
    // Send message between employee and customer
     
    public function sendMessage(Booking $booking, int $senderId, string $message): BookingMessage
    {
        // Only allow for the booking or the designated employee
        if (
            $senderId !== $booking->user_id &&
            $senderId !== $booking->employee_id
        ) {
            throw new AuthorizationException('Not allowed to send message for this booking.');
        }

        return $booking->messages()->create([
            'sender_id' => $senderId,
            'message'   => $message,
        ]);
    }

    // Get booking conversation
    public function getMessages(Booking $booking, int $userId)
    {
        if (
            $userId !== $booking->user_id &&
            $userId !== $booking->employee_id
        ) {
            throw new AuthorizationException('Not allowed to view messages.');
        }

        return $booking->messages()
            ->with('sender:id,name')
            ->orderBy('created_at')
            ->get();
    }
}

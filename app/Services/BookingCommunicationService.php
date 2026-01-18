<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingMessage;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Service class to handle communication between customers and employees.
 */
class BookingCommunicationService
{
    /**
     * Send a new message within a specific booking context.
     *
     * @param  Booking  $booking  The booking instance
     * @param  int  $senderId  The ID of the user sending the message
     *
     * @throws AuthorizationException If the sender is neither the client nor the assigned employee
     */
    public function sendMessage(Booking $booking, int $senderId, string $message): BookingMessage
    {
        // Cast IDs to integer to ensure strict comparison works correctly
        $ownerId = (int) $booking->user_id;
        $assignedEmployeeId = (int) $booking->employee_id;

        // Authorization Check: Only the booking owner or the assigned employee can send messages
        if ($senderId !== $ownerId && $senderId !== $assignedEmployeeId) {
            throw new AuthorizationException('Access Denied: You are not authorized to send messages for this booking.');
        }

        // Create and return the message
        return $booking->messages()->create([
            'sender_id' => $senderId,
            'message' => $message,
        ]);
    }

    /**
     * Retrieve the conversation history for a specific booking.
     *
     * @param  Booking  $booking  The booking instance
     * @param  int  $userId  The ID of the user requesting the messages
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @throws AuthorizationException If the user is not part of the conversation
     */
    public function getMessages(Booking $booking, int $userId)
    {
        $ownerId = (int) $booking->user_id;
        $assignedEmployeeId = (int) $booking->employee_id;

        // Authorization Check: Only participants can view the message history
        if ($userId !== $ownerId && $userId !== $assignedEmployeeId) {
            throw new AuthorizationException('Access Denied: You are not authorized to view these messages.');
        }

        // Fetch messages with sender details, ordered chronologically
        return $booking->messages()
            ->with('sender:id,name')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}

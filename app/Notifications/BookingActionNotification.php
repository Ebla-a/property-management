<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $action,   // created | approved | cancelled
        public int $bookingId,
        public string $byUser
    ) {}

    /**
     * Channels
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Data stored in notifications table
     */
    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Booking #{$this->bookingId} {$this->action}",
            'by'      => $this->byUser,
            'type'    => 'booking',
        ];
    }
}

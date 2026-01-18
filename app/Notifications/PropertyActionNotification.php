<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PropertyActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $action,        // created | updated | deleted
        public string $propertyTitle, // property name
        public string $byUser         // user name
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
            'message' => "Property '{$this->propertyTitle}' {$this->action}",
            'by'      => $this->byUser,
            'type'    => 'property',
        ];
    }
}

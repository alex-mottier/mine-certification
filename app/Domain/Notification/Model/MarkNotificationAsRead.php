<?php

namespace App\Domain\DTO\Notification;

class MarkNotificationAsRead
{
    public function __construct(
        protected array $notifications,
    ){
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}

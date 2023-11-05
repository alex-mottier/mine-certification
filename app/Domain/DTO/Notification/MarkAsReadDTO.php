<?php

namespace App\Domain\DTO\Notification;

class MarkAsReadDTO
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

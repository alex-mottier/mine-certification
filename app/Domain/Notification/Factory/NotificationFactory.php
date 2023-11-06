<?php

namespace App\Domain\Notification\Factory;

use App\Domain\DTO\Notification\NotificationDTO;
use Illuminate\Notifications\DatabaseNotification;

class NotificationFactory
{

    public function fromModel(DatabaseNotification $notification): NotificationDTO
    {
        return new NotificationDTO(
            id: $notification->id,
            content: $notification->data
        );
    }
}

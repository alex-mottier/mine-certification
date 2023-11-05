<?php

namespace App\Domain\Factory\Notification;

use App\Domain\DTO\Notification\MarkAsReadDTO;
use App\Http\Requests\Api\Notification\MarkNotificationAsReadRequest;

class MarkAsReadFactory
{

    public function fromRequest(MarkNotificationAsReadRequest $request): MarkAsReadDTO
    {
        return new MarkAsReadDTO(
            notifications: $request->validated('notifications')
        );
    }
}

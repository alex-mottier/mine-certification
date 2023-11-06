<?php

namespace App\Domain\Notification\Factory;

use App\Domain\DTO\Notification\MarkNotificationAsRead;
use App\Http\Requests\Notification\MarkNotificationAsReadRequest;

class MarkNotificationAsReadFactory
{

    public function fromRequest(MarkNotificationAsReadRequest $request): MarkNotificationAsRead
    {
        return new MarkNotificationAsRead(
            notifications: $request->validated('notifications')
        );
    }
}

<?php

namespace App\Domain\DTO\Notification;

use JsonSerializable;

class NotificationDTO implements JsonSerializable
{
    public function __construct(
        protected string $id,
        protected array $content
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content
        ];
    }
}

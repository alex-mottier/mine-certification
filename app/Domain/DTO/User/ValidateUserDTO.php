<?php

namespace App\Domain\DTO\User;

use App\Domain\Status\Status;

readonly class ValidateUserDTO
{
    public function __construct(
        protected int $userId,
        protected Status $status
    )
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}

<?php

namespace App\Domain\DTO\Report;

use App\Domain\Status\Status;
use App\Models\User;
use JsonSerializable;

readonly class UpgradeReportDTO implements JsonSerializable
{
    /**
     * @param Status $status
     * @param User|null $user
     */
    public function __construct(
        protected Status $status,
        protected ?User $user,
    )
    {
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status->value
        ];
    }
}

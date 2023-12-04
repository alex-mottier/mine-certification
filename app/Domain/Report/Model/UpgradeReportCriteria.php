<?php

namespace App\Domain\Report\Model;

use App\Domain\Status\Status;
use App\Models\User;
use JsonSerializable;

readonly class UpgradeReportCriteria implements JsonSerializable
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

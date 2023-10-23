<?php

namespace App\Domain\DTO\User;

use App\Domain\Status\Status;
use App\Domain\Type\UserType;

readonly class SearchUserDTO
{
    public function __construct(
        protected ?UserType $type,
        protected ?Status   $status,
        protected ?bool $trashed,
        protected ?float $longitude,
        protected ?float $latitude,
        protected ?float $radius,
    )
    {
    }

    public function getType(): ?UserType
    {
        return $this->type;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function withTrashed(): bool
    {
        return $this->trashed ?? false;
    }

    public function hasCoordinates(): bool
    {
        return $this->latitude && $this->longitude && $this->radius;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getRadius(): ?float
    {
        return $this->radius;
    }
}

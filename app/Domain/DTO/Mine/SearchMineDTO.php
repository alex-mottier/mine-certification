<?php

namespace App\Domain\DTO\Mine;

use App\Domain\Status\Status;

readonly class SearchMineDTO
{
    public function __construct(
        protected ?string $name,
        protected ?Status $status,
        protected ?bool $trashed,
        protected ?float $longitude,
        protected ?float $latitude,
        protected ?float $radius
    )
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
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
    

    public function withTrashed(): bool
    {
        return $this->trashed ?? false;
    }

    public function hasCoordinates(): bool
    {
        return $this->latitude && $this->longitude && $this->radius;
    }
}

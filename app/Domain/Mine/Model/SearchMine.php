<?php

namespace App\Domain\Mine\Model;

use App\Domain\Status\Status;

readonly class SearchMine
{
    /**
     * @param string|null $name
     * @param Status|null $status
     * @param bool|null $trashed
     * @param float|null $longitude
     * @param float|null $latitude
     * @param float|null $radius
     * @param int[]|null $users
     */
    public function __construct(
        protected ?string $name,
        protected ?Status $status,
        protected ?bool $trashed,
        protected ?float $longitude,
        protected ?float $latitude,
        protected ?float $radius,
        protected ?array $users
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

    public function getUsers(): ?array
    {
        return $this->users;
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

<?php

namespace App\Domain\Mine\Model;

use App\Domain\Mine\MineType;
use App\Domain\Status\Status;
use JsonSerializable;

readonly class UpdateMine implements JsonSerializable
{
    public function __construct(
        protected int $mineId,
        protected ?string $name,
        protected ?string $email,
        protected ?string $phoneNumber,
        protected ?string $taxNumber,
        protected ?float $longitude,
        protected ?float $latitude,
        protected ?Status $status,
        protected ?MineType $type,
        protected string|array $imagePath
    ){
    }

    public function getMineId(): int
    {
        return $this->mineId;
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'tax_number' => $this->taxNumber,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'status' => $this->status,
            'type' => $this->type,
            'image_path' => $this->imagePath
        ]);
    }
}

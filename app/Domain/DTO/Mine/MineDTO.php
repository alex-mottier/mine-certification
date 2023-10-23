<?php

namespace App\Domain\DTO\Mine;

use App\Domain\Status\Status;
use JsonSerializable;

readonly class MineDTO implements JsonSerializable
{
    public function __construct(
        protected int $id,
        protected string $name,
        protected string $email,
        protected string $phoneNumber,
        protected float $longitude,
        protected float $latitude,
        protected Status $status
    ){
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'status' => $this->status->value
        ];
    }
}

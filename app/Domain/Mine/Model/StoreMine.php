<?php

namespace App\Domain\Mine\Model;

use App\Domain\Status\Status;
use JsonSerializable;

readonly class StoreMine implements JsonSerializable
{
    public function __construct(
        protected string $name,
        protected string $email,
        protected string $phoneNumber,
        protected string $taxNumber,
        protected float $longitude,
        protected float $latitude,
        protected Status $status
    ){
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'tax_number' => $this->taxNumber,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'status' => $this->status
        ];
    }
}

<?php

namespace App\Domain\DTO\Mine;

use JsonSerializable;

readonly class StoreMineDTO implements JsonSerializable
{
    public function __construct(
        protected string $name,
        protected string $email,
        protected string $phoneNumber,
        protected string $taxNumber,
        protected float $longitude,
        protected float $latitude
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
            'latitude' => $this->latitude
        ];
    }
}

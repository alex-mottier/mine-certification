<?php

namespace App\Domain\Institution\Model;

use App\Domain\Institution\InstitutionType;
use App\Domain\Status\Status;
use JsonSerializable;

class StoreInstitution implements JsonSerializable
{
    public function __construct(
        protected string $name,
        protected string $description,
        protected Status $status,
        protected InstitutionType $type,
        protected array $users = [],
        protected array $mines = []
    )
    {
    }
    public function getUsers(): array
    {
        return $this->users;
    }

    public function getMines(): array
    {
        return $this->mines;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status->value,
            'type' => $this->type->value
        ];
    }
}

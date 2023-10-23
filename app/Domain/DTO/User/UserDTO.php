<?php

namespace App\Domain\DTO\User;

use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use Illuminate\Contracts\Support\Arrayable;


readonly class UserDTO implements Arrayable
{
    public function __construct(
        protected int $id,
        protected string   $username,
        protected string   $email,
        protected UserType $type,
        protected Status $status
    )
    {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'type' => $this->type->value,
            'status' => $this->status->value
        ];
    }
}

<?php

namespace App\Domain\DTO\User;

use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use Illuminate\Contracts\Support\Arrayable;

readonly class UpdateUserDTO implements Arrayable
{
    public function __construct(
        protected int $userId,
        protected ?string $username,
        protected ?string $fullname,
        protected ?string $email,
        protected ?string $password,
        protected ?UserType $type,
        protected ?Status $status
    )
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }


    public function toArray(): array
    {
        return array_filter([
            'username' => $this->username,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'password' => $this->password,
            'type' => $this->type,
            'status' => $this->status,
        ]);
    }
}

<?php

namespace App\Domain\User\Model;

use App\Domain\Status\Status;
use App\Domain\User\UserType;
use Illuminate\Contracts\Support\Arrayable;

readonly class StoreUser implements Arrayable
{
    public function __construct(
        protected string $username,
        protected ?string $fullname,
        protected string $email,
        protected string $password,
        protected UserType $type,
        protected Status $status
    )
    {
    }

    public function getType(): UserType
    {
        return $this->type;
    }


    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'password' => $this->password,
            'type' => $this->type,
            'status' => $this->status
        ];
    }
}
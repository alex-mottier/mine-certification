<?php

namespace App\Domain\User\Model;

use App\Domain\Status\Status;
use App\Domain\User\UserType;
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

    public function getId(): int
    {
        return $this->id;
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

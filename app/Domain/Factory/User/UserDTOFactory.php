<?php

namespace App\Domain\Factory\User;

use App\Domain\DTO\User\UserDTO;
use App\Models\User;

class UserDTOFactory
{
    public function fromModel(User $user): UserDTO
    {
        return new UserDTO(
            id: $user->id,
            username: $user->username,
            email: $user->email,
            type: $user->type,
            status: $user->status
        );
    }
}

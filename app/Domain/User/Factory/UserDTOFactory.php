<?php

namespace App\Domain\User\Factory;

use App\Domain\User\Model\UserDTO;
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

<?php

namespace App\Domain\Contract\Service\User;

use App\Domain\DTO\User\StoreUserDTO;
use App\Domain\DTO\User\UserDTO;

interface UserServiceContract
{
    public function store(StoreUserDTO $userStoreDTO): UserDTO;
}

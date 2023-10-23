<?php

namespace App\Domain\Factory\User;

use App\Domain\DTO\User\StoreUserDTO;
use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use App\Http\Requests\Api\User\StoreUserRequest;
use Illuminate\Support\Facades\Hash;

class StoreUserDTOFactory
{
    public function fromRequest(StoreUserRequest $request): StoreUserDTO
    {
        return new StoreUserDTO(
            username: $request->validated('username'),
            fullname: $request->validated('fullname'),
            email: $request->validated('email'),
            password: Hash::make($request->validated('password')),
            type: UserType::tryFrom($request->validated('type')),
            status: Status::CREATED,
        );
    }
}

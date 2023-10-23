<?php

namespace App\Domain\Factory\User;

use App\Domain\DTO\User\UpdateUserDTO;
use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use App\Http\Requests\Api\User\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UpdateUserDTOFactory
{
    public function fromRequest(UpdateUserRequest $request, int $userId): UpdateUserDTO
    {
        return new UpdateUserDTO(
            userId: $userId,
            username: $request->validated('username'),
            fullname: $request->validated('fullname'),
            email: $request->validated('email'),
            password: $request->validated('password') ? Hash::make($request->validated('password')) : null,
            type: UserType::tryFrom($request->validated('type')),
            status: Status::tryFrom($request->validated('status'))
        );
    }
}

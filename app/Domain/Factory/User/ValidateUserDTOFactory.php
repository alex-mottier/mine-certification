<?php

namespace App\Domain\Factory\User;

use App\Domain\DTO\User\ValidateUserDTO;
use App\Domain\Status\Status;
use App\Http\Requests\Api\User\ValidateUserRequest;

class ValidateUserDTOFactory
{

    public function fromRequest(ValidateUserRequest $request, int $userId): ValidateUserDTO
    {
        return new ValidateUserDTO(
            userId: $userId,
            status: Status::tryFrom($request->validated('status'))
        );
    }
}

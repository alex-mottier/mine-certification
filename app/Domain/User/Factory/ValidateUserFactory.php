<?php

namespace App\Domain\User\Factory;

use App\Domain\Status\Status;
use App\Domain\User\Model\ValidateUser;
use App\Http\Requests\User\ValidateUserRequest;

class ValidateUserFactory
{

    public function fromRequest(ValidateUserRequest $request, int $userId): ValidateUser
    {
        return new ValidateUser(
            userId: $userId,
            status: Status::tryFrom($request->validated('status'))
        );
    }
}

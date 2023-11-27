<?php

namespace App\Domain\User\Factory;

use App\Domain\Status\Status;
use App\Domain\User\Model\ValidateUser;
use App\Http\Requests\User\ValidateUserRequest;

class ValidateUserFactory
{

    public function fromRequest(ValidateUserRequest $request): ValidateUser
    {
        return new ValidateUser(
            status: Status::tryFrom($request->validated('status'))
        );
    }

    public function fromStatus(Status $status): ValidateUser
    {
        return new ValidateUser(
            status: $status
        );
    }
}

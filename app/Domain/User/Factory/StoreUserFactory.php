<?php

namespace App\Domain\User\Factory;

use App\Domain\Status\Status;
use App\Domain\User\Model\StoreUser;
use App\Domain\User\UserType;
use App\Http\Requests\User\StoreUserRequest;
use Illuminate\Support\Facades\Hash;

class StoreUserFactory
{
    public function fromRequest(StoreUserRequest $request): StoreUser
    {
        return new StoreUser(
            username: $request->validated('username'),
            fullname: $request->validated('fullname'),
            email: $request->validated('email'),
            password: Hash::make($request->validated('password')),
            type: UserType::tryFrom($request->validated('type')),
            status: Status::CREATED,
        );
    }
}

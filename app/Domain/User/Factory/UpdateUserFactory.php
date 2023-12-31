<?php

namespace App\Domain\User\Factory;

use App\Domain\Status\Status;
use App\Domain\User\Model\UpdateUser;
use App\Domain\User\UserType;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UpdateUserFactory
{
    public function fromRequest(UpdateUserRequest $request, int $userId): UpdateUser
    {
        return new UpdateUser(
            userId: $userId,
            username: $request->validated('username'),
            fullname: $request->validated('fullname'),
            email: $request->validated('email'),
            password: $request->validated('password') ? Hash::make($request->validated('password')) : null,
            longitude: $request->validated('longitude'),
            latitude: $request->validated('latitude'),
            type: UserType::tryFrom($request->validated('type')),
            status: Status::tryFrom($request->validated('status'))
        );
    }

    public function fromArray(array $form): UpdateUser
    {
        return new UpdateUser(
            userId: $form['id'],
            username: $form['username'],
            fullname: $form['fullname'],
            email: $form['email'],
            password: $form['password'] ? Hash::make($form['password']) : null,
            longitude: $form['longitude'],
            latitude: $form['latitude'],
            type: UserType::tryFrom($form['type']),
            status: Status::FOR_VALIDATION
        );
    }
}

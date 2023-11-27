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
            longitude: $request->validated('longitude'),
            latitude: $request->validated('latitude'),
            type: UserType::tryFrom($request->validated('type')),
            status: Status::FOR_VALIDATION,
        );
    }

    public function fromArray(array $form): StoreUser
    {
        return new StoreUser(
            username: $form['username'],
            fullname: $form['fullname'],
            email: $form['email'],
            password: Hash::make($form['password']),
            longitude: $form['longitude'],
            latitude: $form['latitude'],
            type: UserType::tryFrom($form['type']),
            status: Status::FOR_VALIDATION,
        );
    }
}

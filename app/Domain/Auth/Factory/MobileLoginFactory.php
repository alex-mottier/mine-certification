<?php

namespace App\Domain\Auth\Factory;

use App\Domain\Auth\Model\MobileLogin;
use App\Http\Requests\Auth\MobileLoginRequest;

class MobileLoginFactory
{

    public function fromRequest(MobileLoginRequest $request): MobileLogin
    {
        return new MobileLogin(
            username: $request->validated('username'),
            password: $request->validated('password'),
            device_name: $request->validated('device_name')
        );
    }
}

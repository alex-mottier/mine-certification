<?php

namespace App\Domain\Factory\Auth;

use App\Domain\DTO\Auth\MobileLoginDTO;
use App\Http\Requests\Api\Auth\MobileLoginRequest;

class MobileLoginFactory
{

    public function fromRequest(MobileLoginRequest $request): MobileLoginDTO
    {
        return new MobileLoginDTO(
            username: $request->validated('username'),
            password: $request->validated('password'),
            device_name: $request->validated('device_name')
        );
    }
}

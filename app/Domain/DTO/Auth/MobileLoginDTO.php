<?php

namespace App\Domain\DTO\Auth;

readonly class MobileLoginDTO
{
    public function __construct(
        public string $username,
        public string $password,
        public string $device_name
    ){
    }
}

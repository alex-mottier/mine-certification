<?php

namespace App\Domain\Auth\Model;

readonly class MobileLogin
{
    public function __construct(
        public string $username,
        public string $password,
        public string $device_name
    ){
    }
}

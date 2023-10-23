<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

class WrongCredentialsException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            code: 403,
            message: 'Wrong credentials.'
        );
    }
}

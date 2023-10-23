<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

class UnauthorizedException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            code: 401,
            message: 'Not enough access.'
        );
    }
}

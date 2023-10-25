<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

class UserNotValidatedException extends ApiException
{
    public function __construct(?string $message = null)
    {
        parent::__construct(
            code: 403,
            message: 'User is not validated yet or has been refused. ' . $message
        );
    }
}

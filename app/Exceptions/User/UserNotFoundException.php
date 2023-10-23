<?php

namespace App\Exceptions\User;

use App\Exceptions\ApiException;

class UserNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            code: 422,
            message: 'User not found with the ID provided.'
        );
    }
}

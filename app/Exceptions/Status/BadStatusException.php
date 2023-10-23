<?php

namespace App\Exceptions\Status;

use App\Exceptions\ApiException;

class BadStatusException extends ApiException
{
    public function __construct(string $message)
    {
        parent::__construct(
            code: 422,
            message: $message
        );
    }
}

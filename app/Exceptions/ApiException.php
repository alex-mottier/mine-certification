<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    public function __construct(int $code, string $message)
    {
        parent::__construct(
            statusCode: $code,
            message: $message
        );
    }
}

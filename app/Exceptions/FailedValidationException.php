<?php

namespace App\Exceptions;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\Exceptions\HttpResponseException;

class FailedValidationException extends HttpResponseException
{
    public function __construct(MessageBag $bag)
    {
        parent::__construct(response()->json(
            data: [
                'message' => 'There is some errors in your input bag.',
                'errors' => $bag
            ],
            status: 422
        ));
    }
}

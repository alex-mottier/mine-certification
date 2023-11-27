<?php

namespace App\Exceptions\Institution;

use App\Exceptions\ApiException;

class InstitutionNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            code: 422,
            message: 'Institution not found with the ID provided.'
        );
    }
}

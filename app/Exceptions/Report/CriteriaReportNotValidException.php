<?php

namespace App\Exceptions\Report;

use App\Exceptions\ApiException;

class CriteriaReportNotValidException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            code: 422,
            message: 'The form is not valid.'
        );
    }
}

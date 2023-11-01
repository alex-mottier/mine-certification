<?php

namespace App\Exceptions\Report;

use App\Exceptions\ApiException;

class ReportNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            code: 422,
            message: 'Report not found with the ID provided.'
        );
    }
}

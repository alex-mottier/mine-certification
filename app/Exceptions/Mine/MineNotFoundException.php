<?php

namespace App\Exceptions\Mine;

use App\Exceptions\ApiException;

class MineNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            code: 422,
            message: 'Mine not found with the ID provided.'
        );
    }
}

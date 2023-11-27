<?php

namespace App\Domain\User\Model;

use App\Domain\Status\Status;

readonly class ValidateUser
{
    public function __construct(
        protected Status $status
    )
    {
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}

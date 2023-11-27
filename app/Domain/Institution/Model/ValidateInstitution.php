<?php

namespace App\Domain\Institution\Model;

use App\Domain\Status\Status;

class ValidateInstitution
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

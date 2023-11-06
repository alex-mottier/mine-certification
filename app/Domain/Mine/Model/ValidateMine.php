<?php

namespace App\Domain\Mine\Model;

use App\Domain\Status\Status;

readonly class ValidateMine
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

<?php

namespace App\Domain\DTO\Mine;

use App\Domain\Status\Status;

readonly class ValidateMineDTO
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

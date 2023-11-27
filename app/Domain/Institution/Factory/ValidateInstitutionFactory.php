<?php

namespace App\Domain\Institution\Factory;

use App\Domain\Institution\Model\ValidateInstitution;
use App\Domain\Status\Status;

class ValidateInstitutionFactory
{

    public function fromArray(array $data): ValidateInstitution
    {
        return new ValidateInstitution(
            status: Status::from($data['status'])
        );
    }
}

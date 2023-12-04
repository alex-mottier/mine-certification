<?php

namespace App\Domain\Institution\Factory;

use App\Domain\Institution\InstitutionType;
use App\Domain\Institution\Model\UpdateInstitution;
use App\Domain\Status\Status;

class UpdateInstitutionFactory
{

    public function fromArray(array $form): UpdateInstitution
    {
        return new UpdateInstitution(
            id: $form['id'],
            name: $form['name'],
            description: $form['description'],
            status: Status::FOR_VALIDATION,
            type: InstitutionType::from($form['type']),
            users: $form['users'],
        );
    }
}

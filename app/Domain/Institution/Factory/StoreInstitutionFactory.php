<?php

namespace App\Domain\Institution\Factory;

use App\Domain\Institution\InstitutionType;
use App\Domain\Institution\Model\StoreInstitution;
use App\Domain\Status\Status;

class StoreInstitutionFactory
{

    public function fromArray(array $form): StoreInstitution
    {
        return new StoreInstitution(
            name: $form['name'],
            description: $form['description'],
            status: Status::FOR_VALIDATION,
            type: InstitutionType::from($form['type']),
            users: $form['users'],
            mines:  $form['mines'],
        );
    }
}

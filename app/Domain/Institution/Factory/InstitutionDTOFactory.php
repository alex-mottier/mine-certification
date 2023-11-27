<?php

namespace App\Domain\Institution\Factory;

use App\Domain\Institution\Model\InstitutionDTO;
use App\Models\Institution;

class InstitutionDTOFactory
{
    public function fromModel(Institution $institution): InstitutionDTO
    {
        return new InstitutionDTO(
            id: $institution->id
        );
    }
}

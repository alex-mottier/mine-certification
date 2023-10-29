<?php

namespace App\Domain\Factory\Criteria;

use App\Domain\DTO\Criteria\CriteriaDTO;
use App\Models\Criteria;

class CriteriaDTOFactory
{
    public function fromModel(Criteria $criteria): CriteriaDTO
    {
        return new CriteriaDTO(
            id: $criteria->id,
            name: $criteria->name,
            description: $criteria->description,
            quota: $criteria->quota,
            chapterId: $criteria->chapter()->first()->id
        );
    }
}

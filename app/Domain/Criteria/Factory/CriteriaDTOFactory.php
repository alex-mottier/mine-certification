<?php

namespace App\Domain\Criteria\Factory;

use App\Domain\Criteria\Model\CriteriaDTO;
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

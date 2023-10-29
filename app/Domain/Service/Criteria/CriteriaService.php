<?php

namespace App\Domain\Service\Criteria;

use App\Domain\DTO\Criteria\CriteriaDTO;
use App\Domain\DTO\Criteria\SearchCriteriaDTO;
use App\Domain\Factory\Criteria\CriteriaDTOFactory;
use App\Models\Criteria;

readonly class CriteriaService
{
    public function __construct(
        protected CriteriaDTOFactory $factory
    )
    {
    }

    /**
     * @param SearchCriteriaDTO $search
     * @return CriteriaDTO[]
     */
    public function list(SearchCriteriaDTO $search): array
    {
        $criterias = [];

        $query = Criteria::query();
        foreach ($query->get() as $criteria){
            $criterias[] = $this->factory->fromModel($criteria);
        }

        return $criterias;
    }
}

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

        if($search->getName() || $search->getDescription()){
            $query->where('name', 'like', '%'.$search->getName()??$search->getDescription().'%');
            $query->orWhere('name', 'like', '%'.$search->getDescription()??$search->getName().'%');
            $query->where('description', 'like', '%'.$search->getName()??$search->getDescription().'%');
            $query->orWhere('description', 'like', '%'.$search->getDescription()??$search->getName().'%');
        }

        if($search->getQuota()){
            $query->where('quota', $search->getQuota());
        }

        if($search->getChapters()){
            foreach ($search->getChapters() as $chapter){
                $query->where('chapter_id', $chapter);
            }
        }

        foreach ($query->get() as $criteria){
            $criterias[] = $this->factory->fromModel($criteria);
        }

        return $criterias;
    }
}

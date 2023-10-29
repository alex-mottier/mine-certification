<?php

namespace App\Domain\Factory\Chapter;

use App\Domain\DTO\Chapter\ChapterDTO;
use App\Domain\Factory\Criteria\CriteriaDTOFactory;
use App\Models\Chapter;

readonly class ChapterDTOFactory
{
    public function __construct(
        protected CriteriaDTOFactory $criteriaFactory
    )
    {
    }

    public function fromModel(Chapter $chapter): ChapterDTO
    {
        $criterias = [];
        foreach ($chapter->criterias()->get() as $criteriaModel){
            $criteria = $this->criteriaFactory->fromModel($criteriaModel);
            $criterias[] = $criteria->jsonSerialize();
        }

        return new ChapterDTO(
            id: $chapter->id,
            name: $chapter->name,
            description: $chapter->description,
            quota: $chapter->quota,
            criterias: $criterias
        );
    }
}

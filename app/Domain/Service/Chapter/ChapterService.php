<?php

namespace App\Domain\Service\Chapter;

use App\Domain\DTO\Chapter\ChapterDTO;
use App\Domain\DTO\Chapter\SearchChapterDTO;
use App\Domain\Factory\Chapter\ChapterDTOFactory;
use App\Models\Chapter;

readonly class ChapterService
{
    public function __construct(
        protected ChapterDTOFactory $factory
    )
    {
    }

    /**
     * @param SearchChapterDTO $search
     * @return ChapterDTO[]
     */
    public function list(SearchChapterDTO $search): array
    {
        $query = Chapter::query();
        $chapters = [];

        foreach ($query->get() as $chapter){
            $chapters[] = $this->factory->fromModel($chapter);
        }

        return $chapters;
    }
}

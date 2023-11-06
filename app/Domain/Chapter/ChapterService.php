<?php

namespace App\Domain\Chapter;

use App\Domain\Chapter\Factory\ChapterDTOFactory;
use App\Domain\Chapter\Model\ChapterDTO;
use App\Domain\Chapter\Model\SearchChapter;
use App\Models\Chapter;

readonly class ChapterService
{
    public function __construct(
        protected ChapterDTOFactory $factory
    )
    {
    }

    /**
     * @param SearchChapter $search
     * @return ChapterDTO[]
     */
    public function list(SearchChapter $search): array
    {
        $query = Chapter::query();
        $chapters = [];

        foreach ($query->get() as $chapter){
            $chapters[] = $this->factory->fromModel($chapter);
        }

        return $chapters;
    }
}

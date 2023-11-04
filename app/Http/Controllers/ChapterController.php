<?php

namespace App\Http\Controllers;

use App\Domain\Factory\Chapter\SearchChapterDTOFactory;
use App\Domain\Service\Chapter\ChapterService;
use App\Http\Requests\Api\Chapter\SearchChapterRequest;
use App\Http\Resources\Chapter\ChapterCollection;

class ChapterController extends Controller
{
    public function __construct(
        protected ChapterService $service,
        protected SearchChapterDTOFactory $searchFactory,
    )
    {
    }

    public function index(SearchChapterRequest $request): ChapterCollection
    {
        $chapters = $this->service->list(
            $this->searchFactory->fromRequest($request)
        );

        return new ChapterCollection($chapters);
    }
}

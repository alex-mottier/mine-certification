<?php

namespace App\Http\Controllers;

use App\Domain\Factory\Chapter\SearchChapterDTOFactory;
use App\Domain\Service\Chapter\ChapterService;
use App\Http\Requests\Api\Chapter\SearchChapterRequest;
use App\Http\Resources\Chapter\ChapterCollection;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Tag;

#[Tag(
    name:'Chapter',
    description: 'Endpoint to handle "Chapter" requests'
)]
class ChapterController extends Controller
{
    public function __construct(
        protected ChapterService $service,
        protected SearchChapterDTOFactory $searchFactory,
    )
    {
    }

    #[Get(
        path: '/api/v1/chapters',
        operationId: 'List chapters',
        description: 'List chapters',
        tags: [
            'Chapter'
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'List chapters',
                content: new JsonContent(
                    ref: '#/components/schemas/ChapterCollection'
                )
            )
        ]
    )]
    public function index(SearchChapterRequest $request): ChapterCollection
    {
        $chapters = $this->service->list(
            $this->searchFactory->fromRequest($request)
        );

        return new ChapterCollection($chapters);
    }
}

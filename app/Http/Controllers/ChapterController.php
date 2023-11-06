<?php

namespace App\Http\Controllers;

use App\Domain\Chapter\ChapterService;
use App\Domain\Chapter\Factory\SearchChapterFactory;
use App\Http\Requests\Chapter\SearchChapterRequest;
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
        protected ChapterService       $service,
        protected SearchChapterFactory $searchFactory,
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

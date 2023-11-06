<?php

namespace App\Http\Controllers;

use App\Domain\Reaction\Factory\SearchReactionFactory;
use App\Domain\Reaction\Factory\StoreReactionFactory;
use App\Domain\Reaction\ReactionService;
use App\Http\Requests\Reaction\SearchReactionRequest;
use App\Http\Requests\Reaction\StoreReactionRequest;
use App\Http\Resources\Reaction\ReactionCollection;
use App\Http\Resources\Reaction\ReactionResource;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Tag;

#[Tag(
    name:'Reaction',
    description: 'Endpoint to handle "Reaction" requests'
)]
class ReactionController extends Controller
{
    public function __construct(
        protected ReactionService       $service,
        protected SearchReactionFactory $searchFactory,
        protected StoreReactionFactory  $storeFactory,
    ){
    }

    #[Get(
        path: '/api/v1/reactions',
        operationId: 'List reactions',
        description: 'List reactions',
        security: [
            ['apiToken' => []]
        ],
        tags: [
            'Reaction'
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'List reactions',
                content: new JsonContent(
                    ref: '#/components/schemas/ReactionCollection'
                )
            )
        ]
    )]
    public function index(SearchReactionRequest $request): ReactionCollection
    {
        $reactions = $this->service->list(
            $this->searchFactory->fromRequest($request)
        );

        return new ReactionCollection($reactions);
    }

    #[Post(
        path: '/api/v1/reactions',
        operationId: 'Create a reaction',
        description: 'Create a reaction',
        security: [
            ['apiToken' => []]
        ],
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/StoreReactionRequest'
            )
        ),
        tags: [
            'Reaction'
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'Create a reaction',
                content: new JsonContent(
                    ref: '#/components/schemas/ReactionResource'
                )
            )
        ]
    )]
    public function store(StoreReactionRequest $request): ReactionResource
    {
        $reaction = $this->service->store(
            $this->storeFactory->fromRequest($request)
        );

        return new ReactionResource($reaction);
    }
}

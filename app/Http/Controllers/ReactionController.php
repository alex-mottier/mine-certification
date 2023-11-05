<?php

namespace App\Http\Controllers;

use App\Domain\Factory\Reaction\SearchReactionDTOFactory;
use App\Domain\Factory\Reaction\StoreReactionDTOFactory;
use App\Domain\Service\Reaction\ReactionService;
use App\Http\Requests\Api\Reaction\SearchReactionRequest;
use App\Http\Requests\Api\Reaction\StoreReactionRequest;
use App\Http\Resources\Reaction\ReactionCollection;
use App\Http\Resources\Reaction\ReactionResource;

class ReactionController extends Controller
{
    public function __construct(
        protected ReactionService $service,
        protected SearchReactionDTOFactory $searchFactory,
        protected StoreReactionDTOFactory $storeFactory,
    ){
    }

    public function index(SearchReactionRequest $request): ReactionCollection
    {
        $reactions = $this->service->list(
            $this->searchFactory->fromRequest($request)
        );

        return new ReactionCollection($reactions);
    }

    public function store(StoreReactionRequest $request): ReactionResource
    {
        $reaction = $this->service->store(
            $this->storeFactory->fromRequest($request)
        );

        return new ReactionResource($reaction);
    }
}

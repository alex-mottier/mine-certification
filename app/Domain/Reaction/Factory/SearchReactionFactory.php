<?php

namespace App\Domain\Reaction\Factory;

use App\Domain\Reaction\Model\SearchReaction;
use App\Http\Requests\Reaction\SearchReactionRequest;

class SearchReactionFactory
{

    public function fromRequest(SearchReactionRequest $request): SearchReaction
    {
        return new SearchReaction();
    }
}

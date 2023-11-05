<?php

namespace App\Domain\Factory\Reaction;

use App\Domain\DTO\Reaction\SearchReactionDTO;
use App\Http\Requests\Api\Reaction\SearchReactionRequest;

class SearchReactionDTOFactory
{

    public function fromRequest(SearchReactionRequest $request): SearchReactionDTO
    {
        return new SearchReactionDTO();
    }
}

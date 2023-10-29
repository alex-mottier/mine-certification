<?php

namespace App\Domain\Factory\Criteria;

use App\Domain\DTO\Criteria\SearchCriteriaDTO;
use App\Http\Requests\Api\Criteria\SearchCriteriaRequest;

class SearchCriteriaDTOFactory
{
    public function fromRequest(SearchCriteriaRequest $request): SearchCriteriaDTO
    {
        return new SearchCriteriaDTO(
            name: $request->validated('name'),
            description: $request->validated('description'),
            quota: $request->validated('quota'),
            chapters: $request->validated('chapters')
        );
    }
}

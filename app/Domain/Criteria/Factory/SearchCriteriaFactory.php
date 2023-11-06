<?php

namespace App\Domain\Criteria\Factory;

use App\Domain\Criteria\Model\SearchCriteria;
use App\Http\Requests\Criteria\SearchCriteriaRequest;

class SearchCriteriaFactory
{
    public function fromRequest(SearchCriteriaRequest $request): SearchCriteria
    {
        return new SearchCriteria(
            name: $request->validated('name'),
            description: $request->validated('description'),
            quota: $request->validated('quota'),
            chapters: $request->validated('chapters')
        );
    }
}

<?php

namespace App\Domain\Factory\Chapter;

use App\Domain\DTO\Chapter\SearchChapterDTO;
use App\Http\Requests\Api\Chapter\SearchChapterRequest;

class SearchChapterDTOFactory
{
    public function fromRequest(SearchChapterRequest $request): SearchChapterDTO
    {
        return new SearchChapterDTO(
            name: $request->validated('name'),
            description: $request->validated('description'),
            quota: $request->validated('quota')
        );
    }
}

<?php

namespace App\Domain\Chapter\Factory;

use App\Domain\Chapter\Model\SearchChapter;
use App\Http\Requests\Chapter\SearchChapterRequest;

class SearchChapterFactory
{
    public function fromRequest(SearchChapterRequest $request): SearchChapter
    {
        return new SearchChapter(
            name: $request->validated('name'),
            description: $request->validated('description'),
            quota: $request->validated('quota')
        );
    }
}

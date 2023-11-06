<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\Model\SearchMine;
use App\Domain\Status\Status;
use App\Http\Requests\Mine\SearchMineRequest;

class SearchMineFactory
{

    public function fromRequest(SearchMineRequest $request): SearchMine
    {
        return new SearchMine(
            name: $request->validated('name'),
            status: Status::tryFrom($request->validated('status')),
            trashed: $request->validated('trashed'),
            longitude: $request->validated('longitude'),
            latitude: $request->validated('latitude'),
            radius: $request->validated('radius'),
            users: $request->validated('users'),
        );
    }
}

<?php

namespace App\Domain\Factory\Mine;

use App\Domain\DTO\Mine\SearchMineDTO;
use App\Domain\Status\Status;
use App\Http\Requests\Api\Mine\SearchMineRequest;

class SearchMineDTOFactory
{

    public function fromRequest(SearchMineRequest $request): SearchMineDTO
    {
        return new SearchMineDTO(
            name: $request->validated('name'),
            status: Status::tryFrom($request->validated('status')),
            trashed: $request->validated('trashed'),
            longitude: $request->validated('longitude'),
            latitude: $request->validated('latitude'),
            radius: $request->validated('radius'),
        );
    }
}

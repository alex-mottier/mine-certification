<?php

namespace App\Domain\Factory\User;

use App\Domain\DTO\User\SearchUserDTO;
use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use App\Http\Requests\Api\User\SearchUserRequest;

class SearchUserDTOFactory
{
    public function fromRequest(SearchUserRequest $request): SearchUserDTO
    {
        return new SearchUserDTO(
            type: UserType::tryFrom($request->validated('type')),
            status: Status::tryFrom($request->validated('status')),
            trashed: $request->validated('trashed'),
            longitude: $request->validated('longitude'),
            latitude: $request->validated('latitude'),
            radius: $request->validated('radius'),
        );
    }
}

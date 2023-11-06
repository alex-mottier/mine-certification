<?php

namespace App\Domain\User\Factory;

use App\Domain\Status\Status;
use App\Domain\User\Model\SearchUser;
use App\Domain\User\UserType;
use App\Http\Requests\User\SearchUserRequest;

class SearchUserFactory
{
    public function fromRequest(SearchUserRequest $request): SearchUser
    {
        return new SearchUser(
            type: UserType::tryFrom($request->validated('type')),
            status: Status::tryFrom($request->validated('status')),
            trashed: $request->validated('trashed'),
            longitude: $request->validated('longitude'),
            latitude: $request->validated('latitude'),
            radius: $request->validated('radius'),
        );
    }
}

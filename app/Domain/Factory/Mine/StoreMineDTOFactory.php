<?php

namespace App\Domain\Factory\Mine;

use App\Domain\DTO\Mine\StoreMineDTO;
use App\Domain\Status\Status;
use App\Http\Requests\Api\Mine\StoreMineRequest;

class StoreMineDTOFactory
{

    public function fromRequest(StoreMineRequest $request): StoreMineDTO
    {
        return new StoreMineDTO(
            name: $request->validated('name'),
            email: $request->validated('email'),
            phoneNumber: $request->validated('phone_number'),
            taxNumber: $request->validated('tax_number'),
            longitude: $request->validated('longitude'),
            latitude: $request->validated('latitude'),
            status: Status::CREATED,
        );
    }
}

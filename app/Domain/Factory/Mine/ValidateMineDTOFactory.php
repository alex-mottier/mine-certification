<?php

namespace App\Domain\Factory\Mine;

use App\Domain\DTO\Mine\ValidateMineDTO;
use App\Domain\Status\Status;
use App\Http\Requests\Api\Mine\ValidateMineRequest;

class ValidateMineDTOFactory
{

    public function fromRequest(ValidateMineRequest $request): ValidateMineDTO
    {
        return new ValidateMineDTO(
            status: Status::tryFrom($request->validated('status'))
        );
    }
}

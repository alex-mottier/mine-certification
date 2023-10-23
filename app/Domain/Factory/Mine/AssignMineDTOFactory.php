<?php

namespace App\Domain\Factory\Mine;

use App\Domain\DTO\Mine\AssignMineDTO;
use App\Http\Requests\Api\Mine\AssignMineRequest;

class AssignMineDTOFactory
{
    public function fromRequest(AssignMineRequest $request): AssignMineDTO
    {
        return new AssignMineDTO(
            certifiers: $request->validated('certifiers')
        );
    }
}

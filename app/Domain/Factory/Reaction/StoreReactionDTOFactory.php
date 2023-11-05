<?php

namespace App\Domain\Factory\Reaction;

use App\Domain\DTO\Reaction\StoreReactionDTO;
use App\Http\Requests\Api\Reaction\StoreReactionRequest;

class StoreReactionDTOFactory
{

    public function fromRequest(StoreReactionRequest $request): StoreReactionDTO
    {
        return new StoreReactionDTO(
            comment: $request->validated('comment'),
            criteriaReportId: $request->validated('criteria_report_id'),
            status: $request->validated('status'),
            attachments: $request->validated('attachments')
        );
    }
}

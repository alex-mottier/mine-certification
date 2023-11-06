<?php

namespace App\Domain\Reaction\Factory;

use App\Domain\Reaction\Model\StoreReaction;
use App\Http\Requests\Reaction\StoreReactionRequest;

class StoreReactionFactory
{

    public function fromRequest(StoreReactionRequest $request): StoreReaction
    {
        return new StoreReaction(
            comment: $request->validated('comment'),
            criteriaReportId: $request->validated('criteria_report_id'),
            status: $request->validated('status'),
            attachments: $request->validated('attachments')
        );
    }
}

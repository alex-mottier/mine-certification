<?php

namespace App\Domain\Factory\Reaction;

use App\Domain\DTO\Reaction\ReactionDTO;
use App\Domain\Status\Status;
use App\Models\Reaction;

class ReactionDTOFactory
{
    public function fromModel(Reaction $reaction): ReactionDTO
    {
        return new ReactionDTO(
            id: $reaction->id,
            comment: $reaction->comment,
            criteriaReportId: $reaction->criteria_report_id,
            createdBy: $reaction->user_id,
            status: Status::from($reaction->status)
        );
    }
}

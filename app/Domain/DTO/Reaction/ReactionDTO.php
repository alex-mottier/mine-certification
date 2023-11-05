<?php

namespace App\Domain\DTO\Reaction;

use App\Domain\Status\Status;
use JsonSerializable;

class ReactionDTO implements JsonSerializable
{
    public function __construct(
        protected int $id,
        protected ?string $comment,
        protected int $criteriaReportId,
        protected int $createdBy,
        protected Status $status
    ){
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'criteria_report_id' => $this->criteriaReportId,
            'created_by' => $this->createdBy,
            'status' => $this->status->value
        ];
    }
}

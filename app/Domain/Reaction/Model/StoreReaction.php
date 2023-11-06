<?php

namespace App\Domain\Reaction\Model;

use App\Domain\Status\Status;
use JsonSerializable;

class StoreReaction implements JsonSerializable
{
    public function __construct(
        protected ?string $comment,
        protected int $criteriaReportId,
        protected string $status,
        protected ?array $attachments,
    ){
    }

    public function getCriteriaReportId(): int
    {
        return $this->criteriaReportId;
    }

    public function getStatus(): Status
    {
        return Status::from($this->status);
    }

    public function getAttachments(): ?array
    {
        return $this->attachments;
    }


    public function jsonSerialize(): array
    {
        return array_filter([
            'criteria_report_id' => $this->criteriaReportId,
            'comment' => $this->comment,
            'status' => $this->status
        ]);
    }
}

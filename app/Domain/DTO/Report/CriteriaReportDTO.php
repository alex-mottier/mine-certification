<?php

namespace App\Domain\DTO\Report;

use JsonSerializable;

readonly class CriteriaReportDTO implements JsonSerializable
{
    /**
     * @param int $id
     * @param int $criteriaId
     * @param int $reportId
     * @param string $comment
     * @param float $score
     * @param string[] $attachments
     */
    public function __construct(
        protected int $id,
        protected int $criteriaId,
        protected int $reportId,
        protected string $comment,
        protected float $score,
        protected array $attachments,
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'criteria_id' => $this->criteriaId,
            'report_id' => $this->reportId,
            'comment' => $this->comment,
            'score' => $this->score,
            'attachments' => $this->attachments
        ];
    }
}

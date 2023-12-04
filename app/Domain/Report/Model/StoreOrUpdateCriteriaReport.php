<?php

namespace App\Domain\Report\Model;

use Illuminate\Http\UploadedFile;
use JsonSerializable;

readonly class StoreOrUpdateCriteriaReport implements JsonSerializable
{
    /**
     * @param int|null $criteriaId
     * @param string|null $comment
     * @param float|null $score
     * @param UploadedFile[] $attachments
     */
    public function __construct(
        protected ?int $criteriaId,
        protected ?string $comment,
        protected ?float $score,
        protected array $attachments,
    )
    {
    }

    public function getCriteriaId(): ?int
    {
        return $this->criteriaId;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'criteria_id' => $this->criteriaId,
            'comment' => $this->comment,
            'score' => $this->score
        ]);
    }
}

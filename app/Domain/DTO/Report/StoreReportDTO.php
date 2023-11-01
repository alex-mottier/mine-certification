<?php

namespace App\Domain\DTO\Report;

use App\Domain\Status\Status;
use App\Domain\Type\ReportType;
use JsonSerializable;

class StoreReportDTO implements JsonSerializable
{
    /**
     * @param string $name
     * @param int $mineId
     * @param ReportType $type
     * @param Status $status
     * @param StoreOrUpdateCriteriaReportDTO[] $criterias
     * @param int|null $createdBy
     */
    public function __construct(
        protected readonly string $name,
        protected readonly int $mineId,
        protected readonly ReportType $type,
        protected Status $status,
        protected readonly array $criterias,
        protected readonly ?int $createdBy
    )
    {
    }

    public function getMineId(): int
    {
        return $this->mineId;
    }

    public function getCriterias(): array
    {
        return $this->criterias;
    }

    public function getType(): ReportType
    {
        return $this->type;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'name' => $this->name,
            'mine_id' => $this->mineId,
            'type' => $this->type->value,
            'status' => $this->status->value,
            'created_by' => $this->createdBy
        ]);
    }
}

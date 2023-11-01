<?php

namespace App\Domain\DTO\Report;

use App\Domain\Status\Status;
use JsonSerializable;

readonly class UpdateReportDTO implements JsonSerializable
{
    /**
     * @param string|null $name
     * @param Status|null $status
     * @param StoreOrUpdateCriteriaReportDTO[] $criterias
     */
    public function __construct(
        protected ?string  $name,
        protected ?Status $status,
        protected ?array  $criterias,
    )
    {
    }

    public function getCriterias(): ?array
    {
        return $this->criterias;
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'name' => $this->name,
            'status' => $this->status?->value,
        ]);
    }
}

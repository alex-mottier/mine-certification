<?php

namespace App\Domain\Report\Model;

use App\Domain\Status\Status;
use JsonSerializable;

readonly class UpdateReport implements JsonSerializable
{
    /**
     * @param string|null $name
     * @param Status|null $status
     * @param StoreOrUpdateCriteriaReport[] $criterias
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

<?php

namespace App\Domain\Report\Model;

use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use JsonSerializable;

readonly class ReportDTO implements JsonSerializable
{
    /**
     * @param int $id
     * @param string $name
     * @param float|null $score
     * @param ReportType $type
     * @param Status $status
     * @param int $mineId
     * @param CriteriaReportDTO[] $criterias
     */
    public function __construct(
        protected int $id,
        protected string $name,
        protected ?float $score,
        protected ReportType $type,
        protected Status $status,
        protected int $mineId,
        protected array $criterias
    )
    {
    }

    public function jsonSerialize(): array
    {
        $criterias = [];
        foreach ($this->criterias as $criteria){
            $criterias[] = $criteria->jsonSerialize();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'score' => $this->score,
            'type' => $this->type->value,
            'status' => $this->status->value,
            'mine_id' => $this->mineId,
            'criterias' => $criterias
        ];
    }
}

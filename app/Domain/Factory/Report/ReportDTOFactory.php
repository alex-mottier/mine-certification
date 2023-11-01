<?php

namespace App\Domain\Factory\Report;

use App\Domain\DTO\Report\ReportDTO;
use App\Models\Report;

readonly class ReportDTOFactory
{
    public function __construct(
        protected CriteriaReportDTOFactory $factory
    )
    {
    }

    public function fromModel(Report $report): ReportDTO
    {
        $criterias = [];
        foreach ($report->criteriaReports()->get() as $criteria){
            $criterias[] = $this->factory->fromModel($criteria);
        }

        return new ReportDTO(
            id: $report->id,
            name: $report->name,
            score: $report->score,
            type: $report->type,
            status: $report->status,
            mineId: $report->mine()->first()->id,
            criterias: $criterias
        );
    }
}

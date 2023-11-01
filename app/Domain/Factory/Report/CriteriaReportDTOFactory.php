<?php

namespace App\Domain\Factory\Report;

use App\Domain\DTO\Report\CriteriaReportDTO;
use App\Models\CriteriaReport;

class CriteriaReportDTOFactory
{
    public function fromModel(CriteriaReport $criteria): CriteriaReportDTO
    {
        $attachments = [];

        foreach ($criteria->attachments()->get() as $attachment) {
            $attachments[] = $attachment->path;
        }
        return new CriteriaReportDTO(
            id: $criteria->id,
            criteriaId: $criteria->criteria_id,
            reportId: $criteria->report_id,
            comment: $criteria->comment,
            score: $criteria->score,
            attachments: $attachments,
        );
    }
}

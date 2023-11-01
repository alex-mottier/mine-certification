<?php

namespace App\Domain\Factory\Report;

use App\Domain\DTO\Report\StoreOrUpdateCriteriaReportDTO;

class StoreOrUpdateCriteriaReportDTOFactory
{
    public function fromArray(array $criteria): StoreOrUpdateCriteriaReportDTO
    {

        return new StoreOrUpdateCriteriaReportDTO(
            criteriaId: array_key_exists('criteria_id', $criteria) ? $criteria['criteria_id']: null,
            comment: array_key_exists('comment', $criteria) ? $criteria['comment']: null,
            score: array_key_exists('score', $criteria) ? $criteria['score']: null,
            attachments: array_key_exists('attachments', $criteria) ? $criteria['attachments']: [],
        );
    }
}

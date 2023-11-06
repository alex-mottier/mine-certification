<?php

namespace App\Domain\Report\Factory;

use App\Domain\Report\Model\StoreOrUpdateCriteriaReport;

class StoreOrUpdateCriteriaReportFactory
{
    public function fromArray(array $criteria): StoreOrUpdateCriteriaReport
    {

        return new StoreOrUpdateCriteriaReport(
            criteriaId: array_key_exists('criteria_id', $criteria) ? $criteria['criteria_id']: null,
            comment: array_key_exists('comment', $criteria) ? $criteria['comment']: null,
            score: array_key_exists('score', $criteria) ? $criteria['score']: null,
            attachments: array_key_exists('attachments', $criteria) ? $criteria['attachments']: [],
        );
    }
}

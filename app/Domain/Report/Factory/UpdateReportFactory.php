<?php

namespace App\Domain\Report\Factory;

use App\Domain\Report\Model\UpdateReport;
use App\Domain\Status\Status;
use App\Http\Requests\Report\UpdateReportRequest;

class UpdateReportFactory
{

    public function __construct(
        protected StoreOrUpdateCriteriaReportFactory $criteriaReportFactory,
    )
    {}

    public function fromRequest(UpdateReportRequest $request): UpdateReport
    {
        $criterias = [];
        $rawCriterias = $request->validated('criterias') ?? [];
        foreach ($rawCriterias as $criteria){
            $criterias[] = $this->criteriaReportFactory->fromArray($criteria);
        }

        return new UpdateReport(
            name: $request->validated('name'),
            status: Status::tryFrom($request->validated('status')),
            criterias: $criterias,
        );
    }

    public function fromArray(array $form): UpdateReport
    {
        $criterias = [];

        foreach ($form['report'] as $report){
            $criterias[] = $this->criteriaReportFactory->fromFront($report);
        }

        return new UpdateReport(
            name: $form['name'],
            status: Status::tryFrom($form['validation'])??Status::CREATED,
            criterias: $criterias,
        );
    }
}

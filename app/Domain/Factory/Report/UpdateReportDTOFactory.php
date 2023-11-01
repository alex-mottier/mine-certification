<?php

namespace App\Domain\Factory\Report;

use App\Domain\DTO\Report\UpdateReportDTO;
use App\Domain\Status\Status;
use App\Http\Requests\Api\Report\UpdateReportRequest;

class UpdateReportDTOFactory
{

    public function __construct(
        protected StoreOrUpdateCriteriaReportDTOFactory $criteriaReportFactory,
    )
    {}

    public function fromRequest(UpdateReportRequest $request): UpdateReportDTO
    {
        $criterias = [];
        $rawCriterias = $request->validated('criterias') ?? [];
        foreach ($rawCriterias as $criteria){
            $criterias[] = $this->criteriaReportFactory->fromArray($criteria);
        }

        return new UpdateReportDTO(
            name: $request->validated('name'),
            status: Status::tryFrom($request->validated('status')),
            criterias: $criterias,
        );
    }
}

<?php

namespace App\Domain\Report\Factory;

use App\Domain\Report\Model\StoreReport;
use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use App\Http\Requests\Report\StoreReportRequest;
use App\Models\User;
use Illuminate\Auth\AuthManager;

readonly class StoreReportFactory
{
    private ?User $user;
    public function __construct(
        protected StoreOrUpdateCriteriaReportFactory $criteriaReportFactory,
        protected AuthManager                        $authManager
    )
    {
        $this->user = $this->authManager->guard('sanctum')->user();
    }

    public function fromRequest(StoreReportRequest $request): StoreReport
    {
        $criterias = [];
        foreach ($request->validated('criterias') as $criteria){
            $criterias[] = $this->criteriaReportFactory->fromArray($criteria);
        }

        return new StoreReport(
            name: $request->validated('name'),
            mineId: $request->validated('mine_id'),
            type: ReportType::tryFrom($request->validated('type')),
            status: Status::CREATED,
            criterias: $criterias,
            createdBy: $this->user?->id
        );
    }
}

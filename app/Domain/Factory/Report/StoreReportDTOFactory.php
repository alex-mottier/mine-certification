<?php

namespace App\Domain\Factory\Report;

use App\Domain\DTO\Report\StoreReportDTO;
use App\Domain\Status\Status;
use App\Domain\Type\ReportType;
use App\Http\Requests\Api\Report\StoreReportRequest;
use App\Models\User;
use Illuminate\Auth\AuthManager;

readonly class StoreReportDTOFactory
{
    private ?User $user;
    public function __construct(
        protected StoreOrUpdateCriteriaReportDTOFactory $criteriaReportFactory,
        protected AuthManager                           $authManager
    )
    {
        $this->user = $this->authManager->guard('sanctum')->user();
    }

    public function fromRequest(StoreReportRequest $request): StoreReportDTO
    {
        $criterias = [];
        foreach ($request->validated('criterias') as $criteria){
            $criterias[] = $this->criteriaReportFactory->fromArray($criteria);
        }

        return new StoreReportDTO(
            name: $request->validated('name'),
            mineId: $request->validated('mine_id'),
            type: ReportType::tryFrom($request->validated('type')),
            status: Status::CREATED,
            criterias: $criterias,
            createdBy: $this->user?->id
        );
    }
}

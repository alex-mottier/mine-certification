<?php

namespace App\Domain\Factory\Report;

use App\Domain\DTO\Report\UpgradeReportDTO;
use App\Domain\Status\Status;
use App\Http\Requests\Api\Report\UpgradeReportRequest;
use App\Models\User;
use Illuminate\Auth\AuthManager;

class UpgradeReportDTOFactory
{

    private ?User $user;
    public function __construct(
        protected AuthManager $authManager,
    )
    {
        $this->user = $this->authManager->guard('sanctum')->user();
    }

    public function fromRequest(UpgradeReportRequest $request): UpgradeReportDTO
    {
        return new UpgradeReportDTO(
            status: Status::tryFrom($request->validated('status')),
            user: $this->user
        );
    }
}

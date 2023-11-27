<?php

namespace App\Domain\Report\Factory;

use App\Domain\Report\Model\UpgradeReport;
use App\Domain\Status\Status;
use App\Http\Requests\Report\UpgradeReportRequest;
use App\Models\User;
use Illuminate\Auth\AuthManager;

class UpgradeReportFactory
{

    private ?User $user;
    public function __construct(
        protected AuthManager $authManager,
    )
    {
        $this->user = $this->authManager->guard('sanctum')->user();
    }

    public function fromRequest(UpgradeReportRequest $request): UpgradeReport
    {
        return new UpgradeReport(
            status: Status::tryFrom($request->validated('status')),
            user: $this->user
        );
    }

    public function fromArray(array $data): UpgradeReport
    {
        return new UpgradeReport(
            status: Status::tryFrom($data['status']),
            user: $this->user
        );
    }
}

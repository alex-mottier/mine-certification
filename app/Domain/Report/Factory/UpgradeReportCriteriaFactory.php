<?php

namespace App\Domain\Report\Factory;

use App\Domain\Report\Model\UpgradeReportCriteria;
use App\Domain\Status\Status;
use App\Models\User;
use Illuminate\Auth\AuthManager;

class UpgradeReportCriteriaFactory
{

    private ?User $user;
    public function __construct(
        protected AuthManager $authManager,
    )
    {
        $this->user = $this->authManager->guard('sanctum')->user();
    }

    public function fromArray(array $data): UpgradeReportCriteria
    {
        return new UpgradeReportCriteria(
            status: Status::tryFrom($data['status']),
            user: $this->user
        );
    }
}

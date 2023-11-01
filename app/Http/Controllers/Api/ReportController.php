<?php

namespace App\Http\Controllers\Api;

use App\Domain\Factory\Report\SearchReportDTOFactory;
use App\Domain\Factory\Report\StoreReportDTOFactory;
use App\Domain\Factory\Report\UpdateReportDTOFactory;
use App\Domain\Factory\Report\UpgradeReportDTOFactory;
use App\Domain\Service\Report\ReportService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Report\SearchReportRequest;
use App\Http\Requests\Api\Report\StoreReportRequest;
use App\Http\Requests\Api\Report\UpdateReportRequest;
use App\Http\Requests\Api\Report\UpgradeReportRequest;
use App\Http\Resources\Report\ReportCollection;
use App\Http\Resources\Report\ReportResource;

class ReportController extends Controller
{
    public function __construct(
        protected readonly ReportService $service,
        protected readonly SearchReportDTOFactory $searchFactory,
        protected readonly StoreReportDTOFactory $storeFactory,
        protected readonly UpdateReportDTOFactory $updateFactory,
        protected readonly UpgradeReportDTOFactory $upgradeFactory,
    )
    {
    }

    public function index(SearchReportRequest $request): ReportCollection
    {
        $reports = $this->service->list(
            $this->searchFactory->fromRequest($request)
        );

        return new ReportCollection($reports);
    }

    public function store(StoreReportRequest $request): ReportResource
    {
        $report = $this->service->store(
            $this->storeFactory->fromRequest($request)
        );

        return new ReportResource($report);
    }

    public function update(UpdateReportRequest $request, int $reportId): ReportResource
    {
        $report = $this->service->update(
            $this->updateFactory->fromRequest($request),
            $reportId
        );

        return new ReportResource($report);
    }

    public function upgrade(UpgradeReportRequest $request, int $reportId): ReportResource
    {
        $report = $this->service->upgrade(
            $this->upgradeFactory->fromRequest($request),
            $reportId
        );

        return new ReportResource($report);
    }
}

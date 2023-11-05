<?php

namespace App\Http\Controllers;

use App\Domain\Factory\Report\SearchReportDTOFactory;
use App\Domain\Factory\Report\StoreReportDTOFactory;
use App\Domain\Factory\Report\UpdateReportDTOFactory;
use App\Domain\Factory\Report\UpgradeReportDTOFactory;
use App\Domain\Service\Report\ReportService;
use App\Http\Requests\Api\Report\SearchReportRequest;
use App\Http\Requests\Api\Report\StoreReportRequest;
use App\Http\Requests\Api\Report\UpdateReportRequest;
use App\Http\Requests\Api\Report\UpgradeReportRequest;
use App\Http\Resources\Report\ReportCollection;
use App\Http\Resources\Report\ReportResource;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Tag;

#[Tag(
    name:'Report',
    description: 'Endpoint to handle "Report" requests'
)]
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

    #[Get(
        path: '/api/v1/reports',
        operationId: 'List reports',
        description: 'List reports',
        security: [
            ['apiToken' => []]
        ],
        tags: [
            'Report'
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'List reports',
                content: new JsonContent(
                    ref: '#/components/schemas/ReportCollection'
                )
            )
        ]
    )]
    public function index(SearchReportRequest $request): ReportCollection
    {
        $reports = $this->service->list(
            $this->searchFactory->fromRequest($request)
        );

        return new ReportCollection($reports);
    }

    #[Post(
        path: '/api/v1/reports',
        operationId: 'Create report',
        description: 'Create report',
        security: [
            ['apiToken' => []]
        ],
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/StoreReportRequest'
            )
        ),
        tags: [
            'Report'
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'Report details',
                content: new JsonContent(
                    ref: '#/components/schemas/ReportResource'
                )
            )
        ]
    )]
    public function store(StoreReportRequest $request): ReportResource
    {
        $report = $this->service->store(
            $this->storeFactory->fromRequest($request)
        );

        return new ReportResource($report);
    }

    #[Post(
        path: '/api/v1/reports/:report_id',
        operationId: 'Edit report',
        description: 'Edit report',
        security: [
            ['apiToken' => []]
        ],
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/UpdateReportRequest'
            )
        ),
        tags: [
            'Report'
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'Report details',
                content: new JsonContent(
                    ref: '#/components/schemas/ReportResource'
                )
            )
        ]
    )]
    public function update(UpdateReportRequest $request, int $reportId): ReportResource
    {
        $report = $this->service->update(
            $this->updateFactory->fromRequest($request),
            $reportId
        );

        return new ReportResource($report);
    }

    #[Patch(
        path: '/api/v1/reports/:report_id',
        operationId: 'Validate / Refuse report',
        description: 'Validate / Refuse report',
        security: [
            ['apiToken' => []]
        ],
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/UpgradeReportRequest'
            )
        ),
        tags: [
            'Report'
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'Report details',
                content: new JsonContent(
                    ref: '#/components/schemas/ReportResource'
                )
            )
        ]
    )]
    public function upgrade(UpgradeReportRequest $request, int $reportId): ReportResource
    {
        $report = $this->service->upgrade(
            $this->upgradeFactory->fromRequest($request),
            $reportId
        );

        return new ReportResource($report);
    }
}

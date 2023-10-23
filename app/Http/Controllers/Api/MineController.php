<?php

namespace App\Http\Controllers\Api;

use App\Domain\Factory\Mine\AssignMineDTOFactory;
use App\Domain\Factory\Mine\SearchMineDTOFactory;
use App\Domain\Factory\Mine\StoreMineDTOFactory;
use App\Domain\Factory\Mine\ValidateMineDTOFactory;
use App\Domain\Service\Mine\MineService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mine\AssignMineRequest;
use App\Http\Requests\Api\Mine\RevokeMineRequest;
use App\Http\Requests\Api\Mine\SearchMineRequest;
use App\Http\Requests\Api\Mine\StoreMineRequest;
use App\Http\Requests\Api\Mine\ValidateMineRequest;
use App\Http\Resources\Mine\MineCollection;
use App\Http\Resources\Mine\MineDetailResource;
use App\Http\Resources\Mine\MineResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Tag;

#[Tag(
    name: 'Mines',
    description: 'Endpoint to handle "Mines" request'
)]
class MineController extends Controller
{
    public function __construct(
        protected readonly MineService $service,
        protected readonly SearchMineDTOFactory $searchFactory,
        protected readonly StoreMineDTOFactory $storeFactory,
        protected readonly ValidateMineDTOFactory $validateFactory,
        protected readonly AssignMineDTOFactory $assignFactory,
    ){
    }

    #[Get(
        path: '/api/v1/mines',
        operationId: 'List mines with filters available',
        description: 'List mines with filters available',
        tags: [
            'Mines'
        ],
        parameters: [
            new QueryParameter(
                name: 'name',
                description: 'Name of mine',
                required: false,
            ),
            new QueryParameter(
                name: 'status',
                description: 'Status of mine',
                required: false,
                example: 'validated'
            ),
            new QueryParameter(
                name: 'trashed',
                description: 'Retrieve also the deleted mines',
                required: false,
                example: 'true'
            ),
            new QueryParameter(
                name: 'lon',
                description: 'Longitude of a position',
                required: false,
                example: '41.40338'
            ),
            new QueryParameter(
                name: 'lat',
                description: 'Latitude of a position',
                required: false,
                example: '2.17403'
            ),
            new QueryParameter(
                name: 'radius',
                description: 'Radius around the position of the given longitude and latitude (in km)',
                required: false,
                example: '50'
            ),
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'List of mine',
                content: new JsonContent(
                    ref: '#/components/schemas/MineCollection'
                )
            )
        ]
    )]
    public function index(SearchMineRequest $request): MineCollection
    {
        $mines = $this->service->list(
            $this->searchFactory->fromRequest($request)
        );

        return new MineCollection($mines);
    }

    #[Get(
        path: '/api/v1/mines/:mine_id',
        operationId: 'List mine with details',
        description: 'List mine with details',
        tags: [
            'Mines'
        ],
        parameters: [
            new PathParameter(
                name: ':mine_id',
                description: 'ID of mine',
                required: true,
            ),
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'List of mine',
                content: new JsonContent(
                    ref: '#/components/schemas/MineDetailResource'
                )
            )
        ]
    )]
    public function view(int $mineId): MineDetailResource
    {
        $mine = $this->service->view($mineId);

        return new MineDetailResource($mine);
    }

    #[Post(
        path: '/api/v1/mines',
        operationId: 'Create a new mine',
        description: 'Create a new mine',
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/StoreMineRequest'
            )
        ),
        tags: [
            'Mines'
        ],
        responses: [
            new OAResponse(
                response: '201',
                description: 'Mine created',
                content: new JsonContent(
                    ref: '#/components/schemas/MineResource'
                )
            )
        ]
    )]
    public function store(StoreMineRequest $request): MineResource
    {
        $mine = $this->service->store(
            $this->storeFactory->fromRequest($request)
        );

        return new MineResource($mine);
    }

    #[Patch(
        path: '/api/v1/mines/:mine_id',
        operationId: 'Validate / Refuse a mine',
        description: 'Validate / Refuse a mine',
        security: [
            ['apiToken' => []]
        ],
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/ValidateMineRequest'
            )
        ),
        tags: [
            'Mines'
        ],
        parameters: [
            new PathParameter(
                name: ':mine_id',
                description: 'ID of mine',
                required: true,
            ),
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: '',
                content: new JsonContent(
                    ref: '#/components/schemas/MineResource'
                )
            )
        ]
    )]
    public function validateMine(ValidateMineRequest $request, int $mineId): MineResource
    {
        $mine = $this->service->validateMine(
            $this->validateFactory->fromRequest($request),
            $mineId
        );

        return new MineResource($mine);
    }

    #[Post(
        path: '/api/v1/mines/:mine_id/users',
        operationId: 'Assign a mine to a certifier',
        description: 'Assign a mine to a certifier',
        security: [
            ['apiToken' => []]
        ],
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/AssignMineRequest'
            )
        ),
        tags: [
            'Mines'
        ],
        parameters: [
            new PathParameter(
                name: ':mine_id',
                description: 'ID of mine',
                required: true,
            ),
        ],
        responses: [
            new OAResponse(
                response: '201',
                description: 'Mine assigned',
            )
        ]
    )]
    public function assign(AssignMineRequest $request, int $mineId): JsonResponse
    {
        $this->service->assign(
            $this->assignFactory->fromRequest($request),
            $mineId
        );

        return response()->json([], 201);
    }

    #[Delete(
        path: '/api/v1/mines/:mine_id/users/:user_id',
        operationId: 'Revoke a mine to a certifier',
        description: 'Revoke a mine to a certifier',
        security: [
            ['apiToken' => []]
        ],
        tags: [
            'Mines'
        ],
        parameters: [
            new PathParameter(
                name: ':mine_id',
                description: 'ID of mine',
                required: true,
            ),
            new PathParameter(
                name: ':user_id',
                description: 'ID of certifier',
                required: true,
            ),
        ],
        responses: [
            new OAResponse(
                response: '204',
                description: 'Mine revoked',
            )
        ]
    )]
    public function revoke(RevokeMineRequest $request, int $mineId, int $userId): JsonResponse
    {
        $this->service->revoke($mineId, $userId);

        return response()->json([], 204);
    }
}

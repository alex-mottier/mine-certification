<?php

namespace App\Http\Controllers;

use App\Domain\User\Factory\SearchUserFactory;
use App\Domain\User\Factory\StoreUserFactory;
use App\Domain\User\Factory\UpdateUserFactory;
use App\Domain\User\Factory\ValidateUserFactory;
use App\Domain\User\UserService;
use App\Http\Requests\User\SearchUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\ValidateUserRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Response;

#[Tag(
    name: 'Users',
    description: 'Endpoint to handle "Users" request'
)]
class UserController extends Controller
{
    public function __construct(
        protected readonly UserService         $service,
        protected readonly StoreUserFactory    $storeFactory,
        protected readonly SearchUserFactory   $searchFactory,
        protected readonly ValidateUserFactory $validateFactory,
        protected readonly UpdateUserFactory   $updateFactory,
    )
    {
    }

    #[Get(
        path: '/api/v1/users',
        operationId: 'List users with filters available',
        description: 'List users with filters available',
        security: [
            ['apiToken' => []]
        ],
        tags: [
            'Users'
        ],
        parameters: [
            new QueryParameter(
                name: 'type',
                description: 'Type of user',
                required: false,
                example: 'certifier'
            ),
            new QueryParameter(
                name: 'status',
                description: 'Status of user',
                required: false,
                example: 'validated'
            ),
            new QueryParameter(
                name: 'trashed',
                description: 'Retrieve also the deleted users',
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
                description: 'List of users',
                content: new JsonContent(
                    ref: '#/components/schemas/UserCollection'
                )
            )
        ]
    )]
    public function index(SearchUserRequest $request): UserCollection
    {
        $users = $this->service->list(
            $this->searchFactory->fromRequest($request)
        );

        return new UserCollection($users);
    }

    #[Post(
        path: '/api/v1/users',
        operationId: 'Create a new user',
        description: 'Create a new user',
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/StoreUserRequest'
            )
        ),
        tags: [
            'Users'
        ],
        responses: [
            new OAResponse(
                response: '201',
                description: 'User created',
                content: new JsonContent(
                    ref: '#/components/schemas/UserResource'
                )
            )
        ]
    )]
    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->service->store(
            $this->storeFactory->fromRequest($request)
        );

        return new UserResource($user);
    }

    #[Put(
        path: '/api/v1/users/:user_id',
        operationId: 'Edit a user',
        description: 'Edit a user',
        security: [
            ['apiToken' => []]
        ],
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/UpdateUserRequest'
            )
        ),
        tags: [
            'Users'
        ],
        parameters: [
            new PathParameter(
                name: ':user_id',
                description: "User's ID",
                required: true,
            )
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'User edited',
                content: new JsonContent(
                    ref: '#/components/schemas/UserResource'
                )
            )
        ]
    )]
    public function update(UpdateUserRequest $request, int $userId): UserResource
    {
        $user = $this->service->update(
            $this->updateFactory->fromRequest($request, $userId)
        );

        return new UserResource($user);
    }

    #[Patch(
        path: '/api/v1/users/:user_id',
        operationId: 'Validate / Refuse a user',
        description: 'Validate / Refuse a user',
        security: [
            ['apiToken' => []]
        ],
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/ValidateUserRequest'
            )
        ),
        tags: [
            'Users'
        ],
        parameters: [
            new PathParameter(
                name: ':user_id',
                description: "User's ID",
                required: true,
            )
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'User validated',
                content: new JsonContent(
                    ref: '#/components/schemas/UserResource'
                )
            )
        ]
    )]
    public function validateUser(ValidateUserRequest $request, int $userId): UserResource
    {
        $user = $this->service->validateUser(
            $this->validateFactory->fromRequest($request, $userId)
        );

        return new UserResource($user);
    }

    #[Delete(
        path: '/api/v1/users/:user_id',
        operationId: 'Delete a user',
        description: 'Delete a user',
        security: [
            ['apiToken' => []]
        ],
        tags: [
            'Users'
        ],
        parameters: [
            new PathParameter(
                name: ':user_id',
                description: "User's ID",
                required: true,
            )
        ],
        responses: [
            new OAResponse(
                response: '204',
                description: 'User deleted',
            )
        ]
    )]
    public function destroy(int $userId): JsonResponse
    {
        $this->service->destroy($userId);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers;

use App\Domain\Factory\Auth\MobileLoginFactory;
use App\Domain\Service\Auth\AuthService;
use App\Http\Requests\Api\Auth\MobileLoginRequest;
use App\Http\Resources\Auth\LoginResource;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Tag;


#[Tag(
    name:'Authentication',
    description: 'Endpoint to handle "Authentication" requests'
)]
class AuthController extends Controller
{
    public function __construct(
        protected readonly AuthService $authService,
        protected readonly MobileLoginFactory $mobileLoginFactory
    ){
    }

    #[Post(
        path: '/api/v1/login',
        operationId: 'Login',
        description: 'Login',
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/MobileLoginRequest'
            )
        ),
        tags: [
            'Authentication'
        ],
        responses: [
            new OAResponse(
                response: '201',
                description: 'User created',
                content: new JsonContent(
                    ref: '#/components/schemas/LoginResource'
                )
            )
        ]
    )]
    public function login(MobileLoginRequest $request): LoginResource
    {
        $token = $this->authService->loginMobile(
            $this->mobileLoginFactory->fromRequest($request)
        );
        return new LoginResource([
            'type' => 'Bearer',
            'token' => $token
        ]);
    }
}

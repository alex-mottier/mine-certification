<?php

namespace App\Http\Controllers;

use App\Domain\Notification\Factory\MarkNotificationAsReadFactory;
use App\Domain\Notification\NotificationService;
use App\Http\Requests\Notification\MarkNotificationAsReadRequest;
use App\Http\Resources\Notification\NotificationCollection;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Tag;

#[Tag(
    name:'Notification',
    description: 'Endpoint to handle "Notification" requests'
)]
class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService           $service,
        protected MarkNotificationAsReadFactory $factory,
    ){
    }

    #[Get(
        path: '/api/v1/notifications',
        operationId: 'List notifications',
        description: 'List notifications',
        security: [
            ['apiToken' => []]
        ],
        tags: [
            'Notification'
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'List notifications',
                content: new JsonContent(
                    ref: '#/components/schemas/NotificationCollection'
                )
            )
        ]
    )]
    public function index(): NotificationCollection
    {
        $notifications = $this->service->list();

        return new NotificationCollection($notifications);
    }

    #[Post(
        path: '/api/v1/notifications',
        operationId: 'Mark notifications as read',
        description: 'Mark notifications as read',
        security: [
            ['apiToken' => []]
        ],
        requestBody: new RequestBody(
            content: new JsonContent(
                ref: '#/components/schemas/MarkNotificationAsReadRequest'
            )
        ),
        tags: [
            'Notification'
        ],
        responses: [
            new OAResponse(
                response: '204',
                description: 'No content.',
            )
        ]
    )]
    public function markAsRead(MarkNotificationAsReadRequest $request): JsonResponse
    {
        $this->service->markAsRead(
            $this->factory->fromRequest($request)
        );

        return response()->json([], 204);
    }
}

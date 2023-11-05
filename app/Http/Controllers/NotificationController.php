<?php

namespace App\Http\Controllers;

use App\Domain\Factory\Notification\MarkAsReadFactory;
use App\Domain\Service\Notification\NotificationService;
use App\Http\Requests\Api\Notification\MarkNotificationAsReadRequest;
use App\Http\Resources\NotificationCollection;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $service,
        protected MarkAsReadFactory $factory,
    ){
    }

    public function index(): NotificationCollection
    {
        $notifications = $this->service->list();

        return new NotificationCollection($notifications);
    }

    public function markAsRead(MarkNotificationAsReadRequest $request): JsonResponse
    {
        $this->service->markAsRead(
            $this->factory->fromRequest($request)
        );

        return response()->json([], 204);
    }
}

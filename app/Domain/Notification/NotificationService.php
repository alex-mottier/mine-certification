<?php

namespace App\Domain\Notification;

use App\Domain\DTO\Notification\MarkNotificationAsRead;
use App\Domain\DTO\Notification\NotificationDTO;
use App\Domain\Notification\Factory\NotificationFactory;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Authenticatable;

class NotificationService
{
    /**
     * @var User $user
     */
    private Authenticatable $user;
    public function __construct(
        protected AuthManager $authManager,
        protected NotificationFactory $factory,
    ){
        $this->user = $this->authManager->guard('sanctum')->user();
    }

    /**
     * @return NotificationDTO[]
     */
    public function list(): array
    {
        $notifications = [];
        $dbNotifications = $this->user->unreadNotifications()->get();
        foreach ($dbNotifications as $notification){
            $notifications[] = $this->factory->fromModel($notification);
        }

        return $notifications;
    }

    public function markAsRead(MarkNotificationAsRead $request): void
    {
        foreach ($request->getNotifications() as $notificationId){
            $notification = $this->user->notifications()->find($notificationId);
            $notification?->markAsRead();
        }
    }
}

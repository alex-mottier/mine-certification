<?php

namespace App\Notifications;

use App\Models\Mine;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MineToValidate extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Mine $mine
    ){
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'content' => 'There is a mine to validate.',
            'mine_id' => $this->mine->id
        ];
    }
}

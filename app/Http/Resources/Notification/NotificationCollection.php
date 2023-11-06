<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'NotificationCollection',
    type: 'array',
    items: new Items(
        ref: '#/components/schemas/NotificationResource'
    )
)]
class NotificationCollection extends ResourceCollection
{
    public static $wrap = 'notifications';
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}

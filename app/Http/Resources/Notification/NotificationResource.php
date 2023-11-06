<?php

namespace App\Http\Resources\Notification;

use App\Domain\DTO\Notification\NotificationDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'NotificationResource',
    properties: [
        new Property(
            property: 'id',
            type: 'string'
        ),
        new Property(
            property: 'content',
            description: "Content of the notification",
            type: 'array',
            items: new Items(
                type: 'string'
            )
        ),
    ],
    type: 'object'
)]
class NotificationResource extends JsonResource
{
    /**
     * @var NotificationDTO $resource
     */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->resource->jsonSerialize();
    }
}

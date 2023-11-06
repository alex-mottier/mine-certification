<?php

namespace App\Http\Resources\User;

use App\Domain\Status\Status;
use App\Domain\User\Model\UserDTO;
use App\Domain\User\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'UserResource',
    properties: [
        new Property(
            property: 'id',
            type: 'integer'
        ),
        new Property(
            property: 'username',
            type: 'string'
        ),
        new Property(
            property: 'email',
            type: 'string'
        ),
        new Property(
            property: 'type',
            type: 'string',
            enum: [UserType::ADMINISTRATOR, UserType::CERTIFIER, UserType::INSTITUTION]
        ),
        new Property(
            property: 'status',
            type: 'string',
            enum: [Status::CREATED, Status::VALIDATED, Status::REFUSED]
        )
    ],
    type: 'object'
)]
class UserResource extends JsonResource
{
    /**
     * @var UserDTO $resource
     */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->resource->toArray();
    }
}

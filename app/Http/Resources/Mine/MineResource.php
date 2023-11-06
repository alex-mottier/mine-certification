<?php

namespace App\Http\Resources\Mine;

use App\Domain\Mine\Model\MineDTO;
use App\Domain\Status\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'MineResource',
    properties: [
        new Property(
            property: 'id',
            type: 'integer'
        ),
        new Property(
            property: 'name',
            type: 'string'
        ),
        new Property(
            property: 'email',
            type: 'string'
        ),
        new Property(
            property: 'phone_number',
            type: 'string',
        ),
        new Property(
            property: 'longitude',
            type: 'string'
        ),
        new Property(
            property: 'latitude',
            type: 'string'
        ),
        new Property(
            property: 'status',
            type: 'string',
            enum: [Status::CREATED, Status::VALIDATED, Status::REFUSED]
        )
    ],
    type: 'object'
)]
class MineResource extends JsonResource
{
    /**
     * @var MineDTO $resource
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

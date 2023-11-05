<?php

namespace App\Http\Resources\Criteria;

use App\Domain\DTO\Criteria\CriteriaDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'CriteriaResource',
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
            property: 'description',
            type: 'string'
        ),
        new Property(
            property: 'quota',
            type: 'float'
        ),
        new Property(
            property: 'chapter_id',
            type: 'integer',
        )
    ],
    type: 'object'
)]
class CriteriaResource extends JsonResource
{
    /**
     * @var CriteriaDTO $resource
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

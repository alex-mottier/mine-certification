<?php

namespace App\Http\Resources\Chapter;

use App\Domain\Chapter\Model\ChapterDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'ChapterResource',
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
            property: 'criterias',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/CriteriaResource'
            )
        )
    ],
    type: 'object'
)]
class ChapterResource extends JsonResource
{
    /**
     * @var ChapterDTO $resource
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

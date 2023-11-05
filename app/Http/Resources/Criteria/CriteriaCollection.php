<?php

namespace App\Http\Resources\Criteria;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'CriteriaCollection',
    type: 'array',
    items: new Items(
        ref: '#/components/schemas/CriteriaResource'
    )
)]
class CriteriaCollection extends ResourceCollection
{
    public static $wrap = 'criterias';
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

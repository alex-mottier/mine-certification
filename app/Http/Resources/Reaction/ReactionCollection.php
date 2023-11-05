<?php

namespace App\Http\Resources\Reaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'ReactionCollection',
    type: 'array',
    items: new Items(
        ref: '#/components/schemas/ReactionResource'
    )
)]
class ReactionCollection extends ResourceCollection
{
    public static $wrap = 'reactions';
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

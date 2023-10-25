<?php

namespace App\Http\Resources\Mine;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'MineCollection',
    type: 'array',
    items: new Items(
        ref: '#/components/schemas/MineResource'
    )
)]
class MineCollection extends ResourceCollection
{
    public static $wrap = 'mines';
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

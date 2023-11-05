<?php

namespace App\Http\Resources\Chapter;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'ChapterCollection',
    type: 'array',
    items: new Items(
        ref: '#/components/schemas/ChapterResource'
    )
)]
class ChapterCollection extends ResourceCollection
{
    public static $wrap = 'chapters';
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

<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'ReportCollection',
    type: 'array',
    items: new Items(
        ref: '#/components/schemas/ReportResource'
    )
)]
class ReportCollection extends ResourceCollection
{
    public static $wrap = 'reports';
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

<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'UserCollection',
    type: 'array',
    items: new Items(
        ref: '#/components/schemas/UserResource'
    )
)]
class UserCollection extends ResourceCollection
{
    public static $wrap = 'users';
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

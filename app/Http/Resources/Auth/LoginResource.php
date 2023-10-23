<?php

namespace App\Http\Resources\Auth;

use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'LoginResource',
    properties: [
        new Property(
            property: 'type',
            type: 'string',
            example: 'Bearer'
        ),
        new Property(
            property: 'token',
            type: 'string'
        ),
    ],
    type: 'object'
)]
class LoginResource extends JsonResource
{
    public static $wrap = '';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->resource;
    }
}

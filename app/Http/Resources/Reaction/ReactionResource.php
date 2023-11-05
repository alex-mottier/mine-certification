<?php

namespace App\Http\Resources\Reaction;

use App\Domain\DTO\Reaction\ReactionDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReactionResource extends JsonResource
{
    public static $wrap = 'reaction';
    /**
     * @var ReactionDTO $resource
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

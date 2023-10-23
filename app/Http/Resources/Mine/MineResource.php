<?php

namespace App\Http\Resources\Mine;

use App\Domain\DTO\Mine\MineDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MineResource extends JsonResource
{
    /**
     * @var MineDTO $resource
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

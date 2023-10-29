<?php

namespace App\Http\Resources\Criteria;

use App\Domain\DTO\Criteria\CriteriaDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CriteriaResource extends JsonResource
{
    /**
     * @var CriteriaDTO $resource
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

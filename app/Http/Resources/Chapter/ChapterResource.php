<?php

namespace App\Http\Resources\Chapter;

use App\Domain\DTO\Chapter\ChapterDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    /**
     * @var ChapterDTO $resource
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

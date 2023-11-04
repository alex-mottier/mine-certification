<?php

namespace App\Http\Resources\Report;

use App\Domain\DTO\Report\ReportDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * @var ReportDTO $resource
     */
    public $resource;
    public static $wrap = 'report';
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

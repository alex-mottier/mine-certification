<?php

namespace App\Http\Resources\Report;

use App\Domain\DTO\Report\ReportDTO;
use App\Domain\Status\Status;
use App\Domain\Type\ReportType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'ReportResource',
    properties: [
        new Property(
            property: 'id',
            type: 'integer'
        ),
        new Property(
            property: 'name',
            type: 'string'
        ),
        new Property(
            property: 'score',
            type: 'float'
        ),
        new Property(
            property: 'type',
            type: 'string',
            enum: [ReportType::REPORT, ReportType::EVALUATION]
        ),
        new Property(
            property: 'status',
            type: 'string',
            enum: [Status::CREATED, Status::FOR_VALIDATION, Status::VALIDATED, Status::REFUSED]
        ),
        new Property(
            property: 'mine_id',
            type: 'integer'
        ),
        new Property(
            property: 'criterias',
            description: "Report based on criterias",
            type: 'array',
            items: new Items(
                properties: [
                    new Property(
                        property: 'criteria_id',
                        type: 'integer',
                    ),
                    new Property(
                        property: 'comment',
                        type: 'string',
                    ),
                    new Property(
                        property: 'score',
                        type: 'float',
                    ),
                    new Property(
                        property: 'attachments',
                        type: 'array',
                        items: new Items(
                            type: 'string'
                        )
                    ),
                    new Property(
                        property: 'criterias',
                        type: '',
                    ),

                ],
                type: 'object'
            )
        ),
    ],
    type: 'object'
)]
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

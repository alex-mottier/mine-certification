<?php

namespace App\Http\Resources\Reaction;

use App\Domain\Reaction\Model\ReactionDTO;
use App\Domain\Status\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'ReactionResource',
    properties: [
        new Property(
            property: 'id',
            type: 'integer'
        ),
        new Property(
            property: 'comment',
            type: 'string'
        ),
        new Property(
            property: 'criteria_report_id',
            type: 'integer'
        ),
        new Property(
            property: 'status',
            type: 'string',
            enum: [Status::CREATED, Status::VALIDATED, Status::REFUSED]
        )
    ],
    type: 'object'
)]
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

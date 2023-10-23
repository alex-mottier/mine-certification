<?php

namespace App\Http\Resources\Mine;

use App\Domain\DTO\Mine\MineDetailDTO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'MineDetailResource',
    properties: [
        new Property(
            property: 'mine',
            ref: '#/components/schemas/MineResource'
        ),
        new Property(
            property: 'certifiers',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/UserResource'
            )
        )
    ],
    type: 'object'
)]
class MineDetailResource extends JsonResource
{
    public static $wrap = '';
    /**
     * @var MineDetailDTO $resource
     */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var User|null $user
         */
        $user = Auth::guard('sanctum')->user();
        $certifiers = [];

        if($user->isAdmin()){
            $certifiers = $this->resource->getCertifiers();
        }

        return array_filter([
            'mine' => $this->resource->getMine()->jsonSerialize(),
            'certifiers' => $certifiers
        ]);
    }
}

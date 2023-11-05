<?php

namespace App\Http\Requests\Api\Reaction;

use App\Domain\Status\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'StoreReactionRequest',
    properties: [
        new Property(
            property: 'comment',
            description: "Comment",
            type: 'string'
        ),
        new Property(
            property: 'criteria_report_id',
            description: "Criteria Report's id",
            type: 'string'
        ),
        new Property(
            property: 'status',
            description: "Reaction's status",
            type: 'string'
        ),
        new Property(
            property: 'attachments',
            description: "Mine's tax number",
            type: 'array',
            items: new Items(
                type: 'string'
            )
        ),
    ],
    type: 'object'
)]
class StoreReactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'comment' => 'nullable|string',
            'criteria_report_id' => 'required|integer|exists:criteria_report,id',
            'status' => ['required', new Enum(Status::class)],
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,bmp,png,jpeg,pdf',
        ];
    }
}

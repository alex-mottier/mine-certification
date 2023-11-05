<?php

namespace App\Http\Requests\Api\Report;

use App\Domain\Status\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'UpdateReportRequest',
    properties: [
        new Property(
            property: 'name',
            description: "Report's name",
            type: 'string'
        ),
        new Property(
            property: 'status',
            description: "Report's type",
            type: 'string',
            enum: [Status::FOR_VALIDATION]
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
class UpdateReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|unique:reports,name',
            'status' => ['nullable', new Enum(Status::class)],
            'criterias' => 'nullable|array',
            'criterias.*.criteria_id' => 'required|exists:criterias,id',
            'criterias.*.comment' => 'nullable|string',
            'criterias.*.score' => 'required|decimal:0,2|lte:10|gte:0',
            'criterias.*.attachments' => 'nullable|array',
            'criterias.*.attachments.*' => 'file|mimes:jpg,bmp,png,jpeg,pdf',
        ];
    }
}

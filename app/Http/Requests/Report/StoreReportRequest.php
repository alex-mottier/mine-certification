<?php

namespace App\Http\Requests\Report;

use App\Domain\Report\ReportType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'StoreReportRequest',
    properties: [
        new Property(
            property: 'name',
            description: "Report's name",
            type: 'string'
        ),
        new Property(
            property: 'mine_id',
            description: "Linked mine",
            type: 'string'
        ),
        new Property(
            property: 'type',
            description: "Report's type",
            type: 'string',
            enum: [ReportType::REPORT, ReportType::EVALUATION]
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
class StoreReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:reports,name',
            'mine_id' => 'required|exists:mines,id',
            'type' => ['required', new Enum(ReportType::class)],
            'criterias' => 'nullable|array',
            'criterias.*.criteria_id' => 'required|exists:criterias,id',
            'criterias.*.comment' => 'required|string',
            'criterias.*.score' => 'required|decimal:0,2|lte:10|gte:0',
            'criterias.*.attachments' => 'nullable|array',
            'criterias.*.attachments.*' => 'file|mimes:jpg,bmp,png,jpeg,pdf',
        ];
    }
}

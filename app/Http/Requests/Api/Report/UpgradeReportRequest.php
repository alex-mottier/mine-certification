<?php

namespace App\Http\Requests\Api\Report;

use App\Domain\Status\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'UpgradeReportRequest',
    properties: [
        new Property(
            property: 'status',
            description: "Report's status",
            type: 'string',
            enum: [Status::VALIDATED, Status::REFUSED]
        ),
    ],
    type: 'object'
)]
class UpgradeReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(Status::class)],
        ];
    }
}

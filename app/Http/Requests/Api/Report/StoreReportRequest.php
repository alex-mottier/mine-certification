<?php

namespace App\Http\Requests\Api\Report;

use App\Domain\Type\ReportType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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

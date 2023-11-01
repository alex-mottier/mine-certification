<?php

namespace App\Http\Requests\Api\Report;

use App\Domain\Status\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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

<?php

namespace App\Http\Requests\Reaction;

use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SearchReactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'type' => ['nullable', new Enum(ReportType::class)],
            'status' => ['nullable', new Enum(Status::class)],
            'mines' => 'nullable|array',
            'mines.*' => 'exists:reports,id|integer'
        ];
    }
}

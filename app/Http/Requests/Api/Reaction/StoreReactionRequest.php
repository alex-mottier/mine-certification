<?php

namespace App\Http\Requests\Api\Reaction;

use App\Domain\Status\Status;
use App\Domain\Type\ReportType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'name' => 'nullable|string',
            'type' => ['nullable', new Enum(ReportType::class)],
            'status' => ['nullable', new Enum(Status::class)],
            'mines' => 'nullable|array',
            'mines.*' => 'exists:reports,id|integer'
        ];
    }
}

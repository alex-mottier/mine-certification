<?php

namespace App\Http\Requests\Api\Reaction;

use App\Domain\Status\Status;
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
            'comment' => 'nullable|string',
            'criteria_report_id' => 'required|integer|exists:criteria_report,id',
            'status' => ['required', new Enum(Status::class)],
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,bmp,png,jpeg,pdf',
        ];
    }
}

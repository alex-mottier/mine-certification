<?php

namespace App\Http\Requests\Chapter;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchChapterRequest extends FormRequest
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
            'description' => 'nullable|string',
            'quota' => 'nullable|decimal:0,2',
            'chapters' => 'nullable|array',
            'chapters.*' => 'exists:chapters,id'
        ];
    }
}

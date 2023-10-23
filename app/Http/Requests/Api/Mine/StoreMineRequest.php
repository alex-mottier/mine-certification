<?php

namespace App\Http\Requests\Api\Mine;

use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreMineRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|regex:/^\+?[1-9][0-9]{7,14}$/',
            'tax_number' => 'required|string',
            'longitude' => 'required|regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            'latitude' => 'required|regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator->errors());
    }
}

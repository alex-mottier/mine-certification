<?php

namespace App\Http\Requests\Mine;

use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'StoreMineRequest',
    properties: [
        new Property(
            property: 'name',
            description: "Mine's name",
            type: 'string'
        ),
        new Property(
            property: 'email',
            description: "Mine's email",
            type: 'string'
        ),
        new Property(
            property: 'phone_number',
            description: "Mine's phone number",
            type: 'string'
        ),
        new Property(
            property: 'tax_number',
            description: "Mine's tax number",
            type: 'string'
        ),
        new Property(
            property: 'longitude',
            description: "Mine's longitude",
            type: 'double'
        ),
        new Property(
            property: 'latitude',
            description: "Mine's longitude",
            type: 'double'
        ),
    ],
    type: 'object'
)]
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
            'phone_number' => 'required|string',
            'tax_number' => 'required|string',
            'longitude' => [
                'required',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
            'latitude' => [
                'required',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator->errors());
    }
}

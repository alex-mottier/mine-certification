<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'StoreUserRequest',
    properties: [
        new Property(
            property: 'username',
            description: "User's username",
            required: ['true'],
            type: 'string'
        ),
        new Property(
            property: 'password',
            description: "User's password",
            type: 'string'
        ),
        new Property(
            property: 'device_name',
            description: "User's device name",
            required: ['true'],
            type: 'string'
        ),
    ],
    type: 'object'
)]
class MobileLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required',
            'device_name' => 'required',
        ];
    }
}

<?php

namespace App\Http\Requests\User;

use App\Domain\User\UserType;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'UpdateUserRequest',
    properties: [
        new Property(
            property: 'username',
            description: "User's username",
            type: 'string'
        ),
        new Property(
            property: 'fullname',
            description: "User's full name",
            type: 'string'
        ),
        new Property(
            property: 'email',
            description: "User's email",
            type: 'string'
        ),
        new Property(
            property: 'password',
            description: "User's password",
            type: 'string'
        ),
        new Property(
            property: 'type',
            description: "User's type",
            type: 'string',
            enum: [UserType::ADMINISTRATOR, UserType::CERTIFIER, UserType::INSTITUTION]
        ),
        new Property(
            property: 'institutions',
            description: "User's institutions",
            type: 'array',
            items: new Items(
                description: "Insitutions's ID",
                type: 'integer'
            )
        ),
    ],
    type: 'object'
)]
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /**
         * @var User $user
         */
        $user = $this->user('sanctum');
        return $user->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'nullable|string|ascii|unique:App\Models\User,username',
            'fullname' => 'nullable|string|ascii',
            'email' => 'nullable|email|unique:App\Models\User,email',
            'password' => 'nullable|string|ascii',
            'type' => [new Enum(UserType::class), 'nullable'],
            'institutions' => [
                Rule::requiredIf(fn() => $this->request->get('type') === UserType::INSTITUTION->value),
                'array'
            ],
            'institutions.*' => 'exists:institutions,id'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            data: [
                'message' => 'There is some errors in your input bag.',
                'errors' => $validator->errors()
            ],
            status: 422
        ));
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json(
            data: [
                'message' => 'Unauthorized.',
            ],
            status: 401
        ));
    }
}

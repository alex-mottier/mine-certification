<?php

namespace App\Http\Requests\Api\User;

use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'ValidateUserRequest',
    properties: [
        new Property(
            property: 'status',
            description: "In wich status will be the user after the operation",
            type: 'string',
            enum: [Status::VALIDATED, Status::REFUSED]
        ),
    ],
    type: 'object'
)]
class ValidateUserRequest extends FormRequest
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
            'status' => ['required', new Enum(Status::class)]
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

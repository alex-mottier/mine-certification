<?php

namespace App\Http\Requests\Api\User;

use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use App\Exceptions\FailedValidationException;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class SearchUserRequest extends FormRequest
{
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
            'type' => ['nullable', new Enum(UserType::class)],
            'status' => ['nullable', new Enum(Status::class)],
            'trashed' => 'nullable|boolean',
            'longitude' => [
                'nullable',
                'required_with:latitude,radius',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
            'latitude' => [
                'nullable',
                'required_with:longitude,radius',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
            ],
            'radius' => 'nullable|required_with:latitude,longitude|decimal:0,2',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator->errors());
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

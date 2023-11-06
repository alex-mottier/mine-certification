<?php

namespace App\Http\Requests\Mine;

use App\Domain\Status\Status;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'ValidateMineRequest',
    properties: [
        new Property(
            property: 'status',
            description: "Mine's status",
            type: 'string',
            enum: [Status::VALIDATED, Status::REFUSED]
        ),
    ],
    type: 'object'
)]
class ValidateMineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /**
         * @var User|null $user
         */
        $user = $this->user('sanctum');
        return $user?->isAdmin() && $user?->isValidated() && $this->mineId;
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
}

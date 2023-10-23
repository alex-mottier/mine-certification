<?php

namespace App\Http\Requests\Api\Mine;

use App\Domain\Status\Status;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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

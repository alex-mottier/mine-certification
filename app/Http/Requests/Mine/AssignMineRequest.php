<?php

namespace App\Http\Requests\Mine;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'AssignMineRequest',
    properties: [
        new Property(
            property: 'certifiers',
            description: "Ids of certifiers to assign",
            type: 'array',
            items: new Items(type: 'integer')
        ),
    ],
    type: 'object'
)]
class AssignMineRequest extends FormRequest
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
        return $user?->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'certifiers' => 'required|array',
            'certifiers.*' => 'required|integer|exists:users,id'
        ];
    }
}

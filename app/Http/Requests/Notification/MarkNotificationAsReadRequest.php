<?php

namespace App\Http\Requests\Notification;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    title: 'MarkNotificationAsReadRequest',
    properties: [
        new Property(
            property: 'notifications',
            description: "Array of notification's ID",
            type: 'array',
            items: new Items(
                type: 'integer'
            )
        ),
    ],
    type: 'object'
)]
class MarkNotificationAsReadRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'notifications' => 'required|array',
            'notifications.*' => 'exists:notifications,id'
        ];
    }
}

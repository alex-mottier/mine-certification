<?php

namespace App\Http\Requests\Api\Notification;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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

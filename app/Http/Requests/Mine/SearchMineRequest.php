<?php

namespace App\Http\Requests\Mine;

use App\Domain\Status\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SearchMineRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
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
            'users' => 'nullable|array',
            'users.*' => 'nullable|integer|exists:users,id'
        ];
    }
}

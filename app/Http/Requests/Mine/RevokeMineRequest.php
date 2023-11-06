<?php

namespace App\Http\Requests\Mine;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RevokeMineRequest extends FormRequest
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
}

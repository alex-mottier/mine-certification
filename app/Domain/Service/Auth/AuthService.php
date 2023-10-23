<?php

namespace App\Domain\Service\Auth;

use App\Domain\DTO\Auth\MobileLoginDTO;
use App\Exceptions\Auth\UserNotValidatedException;
use App\Exceptions\Auth\WrongCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * @throws WrongCredentialsException
     */
    public function loginMobile(MobileLoginDTO $login): string
    {

        /**
         * @var User $user
         */
        $user = User::query()->where('username', $login->username)->first();

        if (! $user || ! Hash::check($login->password, $user->password)) {
            throw new WrongCredentialsException();
        }

        if (!$user->isValidated()) {
            throw new UserNotValidatedException();
        }

        return $user->createToken($login->device_name)->plainTextToken;
    }
}

<?php

namespace App\Domain;

use App\Models\Mine;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SecurityService
{

    public function checkMine(Mine $mine): void
    {
        if(
            !$mine->isValidated() &&
            !Auth::user()?->hasMine($mine->id) &&
            !Auth::user()?->isAdmin()
        ){
            redirect(route('home'));
        }
    }

    public function checkReport(): void
    {
        if(
            !Auth::user()?->isAdmin() &&
            !Auth::user()?->isCertifier()
        ){
            redirect(route('home'));
        }
    }

    public function checkAdmin(): void
    {
        if(
            !Auth::user()?->isAdmin()
        ){
            redirect(route('home'));
        }
    }

    public function checkUser(User $user): void
    {
        if(
            Auth::user()?->id != $user->id &&
            !Auth::user()?->isAdmin()
        ){
            redirect(route('home'));
        }
    }

    public function checkEvaluation(Mine $mine): void
    {
        if(
            !$mine->certifiers->contains('id', Auth::user()?->id)
        ){
            redirect(route('home'));
        }
    }
}

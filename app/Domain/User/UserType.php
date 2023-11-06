<?php

namespace App\Domain\User;

enum UserType: string
{
    case ADMINISTRATOR = 'administrator';
    case CERTIFIER = 'certifier';
    case INSTITUTION = 'institution';
}

<?php

namespace App\Domain\Type;

enum UserType: string
{
    case ADMINISTRATOR = 'administrator';
    case CERTIFIER = 'certifier';
    case INSTITUTION = 'institution';
}

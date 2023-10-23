<?php

namespace App\Domain\Status;

enum Status: string
{
    case CREATED = 'created';
    case VALIDATED = 'validated';
    case FOR_VALIDATION = 'for_validation';
    case REFUSED = 'refused';
}

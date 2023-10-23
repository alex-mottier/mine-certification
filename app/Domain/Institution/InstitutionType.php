<?php

namespace App\Domain\Institution;

enum InstitutionType: string
{
    case ONG = 'ong';
    case REFINERY = 'refinery';
    case BROKER = 'broker';
    case CUSTOMS = 'customs'; // Douane
    case MINE = 'mine';
    case PROCESSING_PLANT = 'processing_plant';
}

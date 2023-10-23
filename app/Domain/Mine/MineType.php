<?php

namespace App\Domain\Mine;

enum MineType: string
{
    case ARTISANAL = 'artisanal';
    case INDUSTRIAL = 'industrial';
    case COOPERATIVE = 'cooperative';
}

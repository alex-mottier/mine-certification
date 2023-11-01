<?php

namespace App\Domain\Type;

enum ReportType: string
{
    case EVALUATION = 'evaluation';
    case REPORT = 'report';
}

<?php

namespace App\Domain\Report;

enum ReportType: string
{
    case EVALUATION = 'evaluation';
    case REPORT = 'report';
}

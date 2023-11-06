<?php

namespace App\Domain\Report\Factory;

use App\Domain\Report\Model\SearchReport;
use App\Http\Requests\Report\SearchReportRequest;

class SearchReportFactory
{
    public function fromRequest(SearchReportRequest $request): SearchReport
    {
        return new SearchReport();
    }
}

<?php

namespace App\Domain\Factory\Report;

use App\Domain\DTO\Report\SearchReportDTO;
use App\Http\Requests\Api\Report\SearchReportRequest;

class SearchReportDTOFactory
{
    public function fromRequest(SearchReportRequest $request): SearchReportDTO
    {
        return new SearchReportDTO();
    }
}

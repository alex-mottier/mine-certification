<?php

namespace App\Http\Controllers\Api;

use App\Domain\Factory\Criteria\SearchCriteriaDTOFactory;
use App\Domain\Service\Criteria\CriteriaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Criteria\SearchCriteriaRequest;
use App\Http\Resources\Criteria\CriteriaCollection;

class CriteriaController extends Controller
{

    public function __construct(
        protected CriteriaService $service,
        protected SearchCriteriaDTOFactory $factory,
    )
    {
    }

    public function index(SearchCriteriaRequest $request): CriteriaCollection
    {
        $criterias = $this->service->list(
            $this->factory->fromRequest($request)
        );

        return new CriteriaCollection($criterias);
    }
}

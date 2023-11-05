<?php

namespace App\Http\Controllers;

use App\Domain\Factory\Criteria\SearchCriteriaDTOFactory;
use App\Domain\Service\Criteria\CriteriaService;
use App\Http\Requests\Api\Criteria\SearchCriteriaRequest;
use App\Http\Resources\Criteria\CriteriaCollection;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Tag;

#[Tag(
    name:'Criteria',
    description: 'Endpoint to handle "Criteria" requests'
)]
class CriteriaController extends Controller
{

    public function __construct(
        protected CriteriaService $service,
        protected SearchCriteriaDTOFactory $factory,
    )
    {
    }

    #[Get(
        path: '/api/v1/criterias',
        operationId: 'List criterias',
        description: 'List criterias',
        tags: [
            'Criteria'
        ],
        responses: [
            new OAResponse(
                response: '200',
                description: 'List criterias',
                content: new JsonContent(
                    ref: '#/components/schemas/CriteriaCollection'
                )
            )
        ]
    )]
    public function index(SearchCriteriaRequest $request): CriteriaCollection
    {
        $criterias = $this->service->list(
            $this->factory->fromRequest($request)
        );

        return new CriteriaCollection($criterias);
    }
}

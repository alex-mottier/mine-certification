<?php

namespace App\Domain\Service\Reaction;

use App\Domain\DTO\Reaction\ReactionDTO;
use App\Domain\DTO\Reaction\SearchReactionDTO;
use App\Domain\DTO\Reaction\StoreReactionDTO;
use App\Domain\Factory\Reaction\ReactionDTOFactory;
use App\Domain\Status\Status;
use App\Exceptions\Auth\UnauthorizedException;
use App\Exceptions\Status\BadStatusException;
use App\Models\CriteriaReport;
use App\Models\Mine;
use App\Models\Reaction;
use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\AuthManager;

class ReactionService
{
    private ?User $user;
    public function __construct(
        protected AuthManager $authManager,
        protected ReactionDTOFactory $factory,
    ){
        $this->user = $this->authManager->guard('sanctum')->user();
    }

    /**
     * @param SearchReactionDTO $search
     * @return ReactionDTO[]
     */
    public function list(SearchReactionDTO $search): array
    {
        $reactions = [];
        $query = Reaction::query();


        foreach ($query->with('criteriaReport.report.mine')->get() as $reaction){
            if(
                $this->user->isAdmin() ||
                ($this->user->isCertifier() &&
                    $this->user->hasMine($reaction->criteriaReport->report->mine->id))
            ){
                $reactions[] = $this->factory->fromModel($reaction);
            }
        }

        return $reactions;
    }

    public function store(StoreReactionDTO $store): ReactionDTO
    {
        /**
         * @var CriteriaReport $criteriaReport
         */
        $criteriaReport = CriteriaReport::query()->find($store->getCriteriaReportId());
        /**
         * @var Report $report
         */
        $report = $criteriaReport->report()->first();
        /**
         * @var Mine $mine
         */
        $mine = $report->mine()->first();
        if(
            !$this->user ||
            !$this->user->isCertifier() ||
            !$this->user->hasMine($mine->id)
        ){
            throw new UnauthorizedException();
        }

        if(
            $store->getStatus() !== Status::VALIDATED &&
            $store->getStatus() !== Status::REFUSED
        ){
            throw new BadStatusException(
                "Status must be 'validated' or 'refused'."
            );
        }

        $payload = array_merge($store->jsonSerialize(), [
            'user_id' => $this->user->id
        ]);

        /**
         * @var Reaction $reaction
         */
        $reaction = Reaction::query()->create($payload);

        return $this->factory->fromModel($reaction);
    }
}

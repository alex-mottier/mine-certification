<?php

namespace App\Domain\Reaction;

use App\Domain\Reaction\Factory\ReactionDTOFactory;
use App\Domain\Reaction\Model\ReactionDTO;
use App\Domain\Reaction\Model\SearchReaction;
use App\Domain\Reaction\Model\StoreReaction;
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
     * @param SearchReaction $search
     * @return ReactionDTO[]
     */
    public function list(SearchReaction $search): array
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

    public function store(StoreReaction $store): ReactionDTO
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

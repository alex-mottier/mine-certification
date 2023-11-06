<?php

namespace App\Domain\Mine;

use App\Domain\Mine\Factory\MineDetailFactory;
use App\Domain\Mine\Factory\MineDTOFactory;
use App\Domain\Mine\Model\AssignMine;
use App\Domain\Mine\Model\MineDetail;
use App\Domain\Mine\Model\MineDTO;
use App\Domain\Mine\Model\SearchMine;
use App\Domain\Mine\Model\StoreMine;
use App\Domain\Mine\Model\ValidateMine;
use App\Domain\Status\Status;
use App\Exceptions\Auth\UnauthorizedException;
use App\Exceptions\Auth\UserNotValidatedException;
use App\Exceptions\Mine\MineNotFoundException;
use App\Exceptions\Status\BadStatusException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\Mine;
use App\Models\User;
use App\Notifications\AssignedMine;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MineService
{
    private ?User $authUser;
    public function __construct(
        protected AuthManager       $authManager,
        protected MineDTOFactory    $mineFactory,
        protected MineDetailFactory $mineDetailFactory,
    )
    {
        $this->authUser = $this->authManager->guard('sanctum')->user();
    }

    /**
     * @param SearchMine $search
     * @return MineDTO[]
     */
    public function list(SearchMine $search): array
    {
        $query = Mine::query();

        if($search->hasCoordinates()){
            $query->inArea($search->getLongitude(), $search->getLatitude(), $search->getRadius());
        }

        if($search->getName()){
            $query->where('name', 'like', '%'.$search->getName().'%');
        }
        if(!$this->authUser){
            $query->where('status', Status::VALIDATED->value);
            return $this->getMines($query);
        }

        if($this->authUser->isAdmin()){
            if($search->withTrashed()){
                $query->withTrashed();
            }

            if($search->getStatus()){
                $query->where('status', $search->getStatus()->value);
            }

            if($search->getUsers()){
                foreach ($search->getUsers() as $userId){
                    $query->where('created_by', $userId);
                }
            }

            return $this->getMines($query);
        }

        if($search->getUsers()){
            foreach ($search->getUsers() as $userId){
                if($userId === $this->authUser->id){
                    $query->where('created_by',$this->authUser->id);
                    return $this->getMines($query);
                }
            }
            return $this->getMines($query);
        }

        $query->where('status', Status::VALIDATED->value);
        $query->orWhere('created_by',$this->authUser->id);

        return $this->getMines($query);
    }

    public function view(int $mineId): MineDetail
    {
        /**
         * @var Mine|null $mine
         */
        $mine = Mine::query()->with('certifiers')->find($mineId);
        if(!$mine){
            throw new MineNotFoundException();
        }
        if((!$this->authUser && $mine->status !== Status::VALIDATED) ||
            ($this->authUser?->isCertifier() && $this->authUser?->id !== $mine->created_by)){
            throw new UnauthorizedException();
        }

        return $this->mineDetailFactory->fromModel($mine);
    }

    public function store(StoreMine $mineDTO): MineDTO
    {
        /**
         * @var Mine $mine
         */
        $mine = Mine::query()->create($mineDTO->jsonSerialize());

        if($this->authUser){
            $mine->created_by = $this->authUser->id;
            $mine->save();
        }
        return $this->mineFactory->fromModel($mine);
    }

    public function validateMine(ValidateMine $request, int $mineId): MineDTO
    {
        /**
         * @var Mine|null $mine
         */
        $mine = Mine::query()->find($mineId);
        if(!$mine){
            throw new MineNotFoundException();
        }

        match($request->getStatus()){
            Status::VALIDATED, Status::REFUSED => $this->checkValidatedRefusedStatus($mine->status),
            Status::FOR_VALIDATION => $this->checkForValidationStatus($mine),
            default => throw new BadStatusException(
                'Status '.Status::FOR_VALIDATION->value .
                ', '.Status::VALIDATED->value .
                ' or ' . Status::REFUSED->value . ' should be provided.'
            )
        };
        
        $mine->status = $request->getStatus();
        $mine->save();

        return $this->mineFactory->fromModel($mine);
    }

    public function assign(AssignMine $request, int $mineId): MineDetail
    {
        /**
         * @var Mine|null $mine
         */
        $mine = Mine::query()->find($mineId);
        if(!$mine){
            throw new MineNotFoundException();
        }

        foreach ($request->getCertifiers() as $certifierId){
            /**
             * @var User $certifier
             */
            $certifier = User::query()->find($certifierId);
            if(!$certifier->isValidated() || !$certifier->isCertifier() || $certifier->hasMine($mineId)){
                throw new UserNotValidatedException(
                    'The cause can also come from the fact that the user is not a certifier'.
                    ' or the certifier is already assigned to the mine.'
                );
            }

            $mine->certifiers()->attach($certifier->id);
            $certifier->notify(new AssignedMine($mine));
        }

        return $this->mineDetailFactory->fromModel($mine);
    }

    public function revoke(int $mineId, int $userId): void
    {
        /**
         * @var Mine|null $mine
         */
        $mine = Mine::query()->find($mineId);
        if(!$mine){
            throw new MineNotFoundException();
        }

        /**
         * @var User|null $user
         */
        $user = User::query()->find($userId);
        if(!$user){
            throw new UserNotFoundException();
        }

        $mine->certifiers()->detach($user->id);
    }

    /**
     * @param BelongsToMany|Builder $query
     * @return MineDTO[]
     */
    private function getMines(BelongsToMany|Builder $query): array
    {
        $mines = [];

        foreach ($query->get() as $mine){
            $mines[] = $this->mineFactory->fromModel($mine);
        }

        return $mines;
    }

    private function checkValidatedRefusedStatus(Status $mineStatus): void
    {
        if(!$this->authUser->isAdmin()){
            throw new UnauthorizedException();
        }

        if($mineStatus !== Status::FOR_VALIDATION){
            throw new BadStatusException(
                'Mine has to be in status: '. Status::FOR_VALIDATION->value .
                '. Current status: '. $mineStatus->value
            );
        }
    }

    private function checkForValidationStatus(Mine $mine): void
    {
        if(
            $mine->created_by !== $this->authUser->id &&
            !$this->authUser->hasMine($mine->id)
        ){
            throw new UnauthorizedException();
        }

        if($mine->status === Status::VALIDATED){
            throw new BadStatusException(
                'Mine has to be in status: '. Status::CREATED->value . ' or ' . Status::REFUSED->value .
                '. Current status: '. $mine->status->value
            );
        }
    }

}

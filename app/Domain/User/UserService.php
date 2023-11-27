<?php

namespace App\Domain\User;

use App\Domain\Mine\Factory\MineDTOFactory;
use App\Domain\Status\Status;
use App\Domain\User\Factory\UserDTOFactory;
use App\Domain\User\Model\SearchUser;
use App\Domain\User\Model\StoreUser;
use App\Domain\User\Model\UpdateUser;
use App\Domain\User\Model\UserDTO;
use App\Domain\User\Model\ValidateUser;
use App\Exceptions\Auth\UnauthorizedException;
use App\Exceptions\Status\BadStatusException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use App\Notifications\UserToValidate;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Notification;

readonly class UserService
{
    private ?User $authUser;
    public function __construct(
        protected AuthManager $authManager,
        protected UserDTOFactory $userFactory,
        protected MineDTOFactory $mineFactory,
    )
    {
        $this->authUser = $this->authManager->guard('sanctum')->user();
    }

    public function store(StoreUser $userStoreDTO): UserDTO
    {
        if($userStoreDTO->getType() === UserType::ADMINISTRATOR &&
            !$this->authUser && !$this->authUser?->isAdmin()
        ){
            throw new UnauthorizedException();
        }

        /**
         * @var User $user
         */
        $user = User::query()->create($userStoreDTO->toArray());

        if($this->authUser){
            $user->created_by = $this->authUser->id;

            if($this->authUser->isAdmin()){
                $user->status = Status::VALIDATED;
                $user->validated_by = $this->authUser->id;
                $user->validated_at = now();
            }
            else{
                $administrators = User::query()->isAdmin()->get();
                Notification::send($administrators, new UserToValidate($user));
            }

            $user->save();
        }

        return $this->userFactory->fromModel($user);
    }

    /**
     * @param SearchUser $search
     * @return UserDTO[]
     */
    public function list(SearchUser $search): array
    {
        $users = [];
        $query = User::query();

        if($search->getStatus()){
            $query = $query->where('status', $search->getStatus());
        }
        if($search->getType()){
            $query = $query->where('type', $search->getType());
        }
        if($search->withTrashed()){
            $query = $query->withTrashed();
        }
        if($search->hasCoordinates()){
            $query = $query->inArea($search->getLongitude(), $search->getLatitude(), $search->getRadius());
        }

        foreach ($query->get() as $user){
            $users[] = $this->userFactory->fromModel($user);
        }

        return $users;
    }

    public function validateUser(ValidateUser $validateUser, int $userId): UserDTO
    {
        if($validateUser->getStatus() === Status::CREATED){
            throw new BadStatusException(
                'Status '.Status::VALIDATED->value . ' or ' . Status::REFUSED->value . ' should be provided.'
            );
        }

        /**
         * @var User|null $user
         */
        $user = User::query()->find($userId);
        if(!$user){
            throw new UserNotFoundException();
        }

        $user->status = $validateUser->getStatus();
        $user->save();

        return $this->userFactory->fromModel($user);
    }

    public function destroy(int $userId): void
    {
        if(!$this->authUser || !$this->authUser->isAdmin()){
            throw new UnauthorizedException();
        }

        /**
         * @var User|null $user
         */
        $user = User::query()->find($userId);
        if(!$user){
            throw new UserNotFoundException();
        }

        $user->delete();
    }

    public function update(UpdateUser $updateUserDTO): UserDTO
    {
        /**
         * @var User|null $user
         */
        $user = User::query()->find($updateUserDTO->getUserId());
        if(!$user){
            throw new UserNotFoundException();
        }

        $user->update($updateUserDTO->toArray());

        /**
         * @var User|null $user
         */
        $user = User::query()->find($updateUserDTO->getUserId());

        return $this->userFactory->fromModel($user);
    }
}

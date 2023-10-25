<?php

namespace App\Domain\Service\User;

use App\Domain\Contract\Service\User\UserServiceContract;
use App\Domain\DTO\User\SearchUserDTO;
use App\Domain\DTO\User\StoreUserDTO;
use App\Domain\DTO\User\UpdateUserDTO;
use App\Domain\DTO\User\UserDTO;
use App\Domain\DTO\User\ValidateUserDTO;
use App\Domain\Factory\Mine\MineDTOFactory;
use App\Domain\Factory\User\UserDTOFactory;
use App\Domain\Status\Status;
use App\Domain\Type\UserType;
use App\Exceptions\Auth\UnauthorizedException;
use App\Exceptions\Status\BadStatusException;
use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use Illuminate\Auth\AuthManager;

readonly class UserService implements UserServiceContract
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

    public function store(StoreUserDTO $userStoreDTO): UserDTO
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

            $user->save();
        }

        return $this->userFactory->fromModel($user);
    }

    /**
     * @param SearchUserDTO $search
     * @return UserDTO[]
     */
    public function list(SearchUserDTO $search): array
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

    public function validateUser(ValidateUserDTO $validateUser): UserDTO
    {
        if($validateUser->getStatus() === Status::CREATED){
            throw new BadStatusException(
                'Status '.Status::VALIDATED->value . ' or ' . Status::REFUSED->value . ' should be provided.'
            );
        }

        /**
         * @var User|null $user
         */
        $user = User::query()->find($validateUser->getUserId());
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

    public function update(UpdateUserDTO $updateUserDTO): UserDTO
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

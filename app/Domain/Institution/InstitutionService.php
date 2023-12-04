<?php

namespace App\Domain\Institution;

use App\Domain\Institution\Factory\InstitutionDTOFactory;
use App\Domain\Institution\Model\InstitutionDTO;
use App\Domain\Institution\Model\StoreInstitution;
use App\Domain\Institution\Model\UpdateInstitution;
use App\Domain\Institution\Model\ValidateInstitution;
use App\Domain\Status\Status;
use App\Exceptions\Auth\UnauthorizedException;
use App\Exceptions\Institution\InstitutionNotFoundException;
use App\Exceptions\Status\BadStatusException;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Auth\AuthManager;

class InstitutionService
{
    private ?User $authUser;
    public function __construct(
        protected AuthManager       $authManager,
        protected InstitutionDTOFactory $institutionDTOFactory,
    )
    {
        $this->authUser = $this->authManager->guard('sanctum')->user();
    }


    public function store(StoreInstitution $data): InstitutionDTO
    {
        /**
         * @var Institution $institution
         */
        $institution = Institution::query()->create($data->jsonSerialize());

        if($this->authUser){
            $institution->created_by = $this->authUser->id;
            $institution->save();
        }

        if($data->getUsers()){
            $institution->users()->sync($data->getUsers());
        }

        return $this->institutionDTOFactory->fromModel($institution);
    }

    public function validate(ValidateInstitution $data, int $id): void
    {
        /**
         * @var Institution|null $institution
         */
        $institution = Institution::query()->find($id);
        if(!$institution){
            throw new InstitutionNotFoundException();
        }

        match($data->getStatus()){
            Status::VALIDATED, Status::REFUSED => $this->checkValidatedRefusedStatus($institution->status),
            Status::FOR_VALIDATION => $this->checkForValidationStatus($institution),
            default => throw new BadStatusException(
                'Status '.Status::FOR_VALIDATION->value .
                ', '.Status::VALIDATED->value .
                ' or ' . Status::REFUSED->value . ' should be provided.'
            )
        };

        $institution->status = $data->getStatus();
        $institution->save();
    }

    private function checkValidatedRefusedStatus(Status $status): void
    {
        if(!$this->authUser->isAdmin()){
            throw new UnauthorizedException();
        }

        if($status !== Status::FOR_VALIDATION){
            throw new BadStatusException(
                'Mine has to be in status: '. Status::FOR_VALIDATION->value .
                '. Current status: '. $status->value
            );
        }
    }

    private function checkForValidationStatus(Institution $institution): void
    {
        if(
            $institution->created_by !== $this->authUser->id &&
            !$this->authUser->hasMine($institution->id)
        ){
            throw new UnauthorizedException();
        }

        if($institution->status === Status::VALIDATED){
            throw new BadStatusException(
                'Mine has to be in status: '. Status::CREATED->value . ' or ' . Status::REFUSED->value .
                '. Current status: '. $institution->status->value
            );
        }
    }

    public function update(UpdateInstitution $data): InstitutionDTO
    {
        /**
         * @var Institution|null $institution
         */
        $institution = Institution::query()->find($data->getId());
        if(!$institution){
            throw new InstitutionNotFoundException();
        }

        $institution->update($data->jsonSerialize());

        $institution->users()->sync($data->getUsers());

        return $this->institutionDTOFactory->fromModel($institution);
    }

}

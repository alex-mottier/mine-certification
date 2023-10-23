<?php

namespace App\Domain\Factory\Mine;

use App\Domain\DTO\Mine\MineDetailDTO;
use App\Domain\DTO\Mine\MineDTO;
use App\Domain\Factory\User\UserDTOFactory;
use App\Models\Mine;
use App\Models\User;
use Ramsey\Collection\Collection;

class MineDetailDTOFactory
{
    public function __construct(
        protected UserDTOFactory $userFactory
    ){
    }

    public function fromModel(Mine $mine): MineDetailDTO
    {
        $certifiers = [];
        /**
         * @var Collection<User> $rawCertifiers
         */
        $rawCertifiers = $mine->certifiers()->get();
        foreach ($rawCertifiers as $certifier){
            $certifiers[] = $this->userFactory->fromModel($certifier);
        }

        return new MineDetailDTO(
            new MineDTO(
                name: $mine->name,
                email: $mine->email,
                phoneNumber: $mine->phone_number,
                longitude: $mine->longitude,
                latitude: $mine->latitude,
                status: $mine->status
            ),
            certifiers: $certifiers
        );
    }
}

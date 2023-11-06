<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\Model\MineDetail;
use App\Domain\Mine\Model\MineDTO;
use App\Domain\User\Factory\UserDTOFactory;
use App\Models\Mine;
use App\Models\User;
use Ramsey\Collection\Collection;

class MineDetailFactory
{
    public function __construct(
        protected UserDTOFactory $userFactory
    ){
    }

    public function fromModel(Mine $mine): MineDetail
    {
        $certifiers = [];
        /**
         * @var Collection<User> $rawCertifiers
         */
        $rawCertifiers = $mine->certifiers()->get();
        foreach ($rawCertifiers as $certifier){
            $certifiers[] = $this->userFactory->fromModel($certifier);
        }

        return new MineDetail(
            new MineDTO(
                id: $mine->id,
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

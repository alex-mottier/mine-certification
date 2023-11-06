<?php

namespace App\Domain\Mine\Model;

use App\Domain\User\Model\UserDTO;

readonly class MineDetail
{
    /**
     * @param MineDTO $mine
     * @param UserDTO[] $certifiers
     */
    public function __construct(
        protected MineDTO $mine,
        protected array $certifiers
    )
    {}

    public function getMine(): MineDTO
    {
        return $this->mine;
    }

    public function getCertifiers(): array
    {
        return array_map(function(UserDTO $certifier){
            return $certifier->toArray();
        }, $this->certifiers);
    }
}

<?php

namespace App\Domain\DTO\Mine;

use App\Domain\DTO\User\UserDTO;

readonly class MineDetailDTO
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

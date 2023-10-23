<?php

namespace App\Domain\DTO\Mine;

readonly class AssignMineDTO
{
    /**
     * @param int[] $certifiers
     */
    public function __construct(
        protected array $certifiers
    ){
    }

    public function getCertifiers(): array
    {
        return $this->certifiers;
    }
}

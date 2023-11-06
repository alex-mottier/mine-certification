<?php

namespace App\Domain\Mine\Model;

readonly class AssignMine
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

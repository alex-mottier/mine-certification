<?php

namespace App\Domain\Mine\Model;

readonly class AssignCertifiersMine
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

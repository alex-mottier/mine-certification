<?php

namespace App\Domain\Mine\Model;

readonly class AssignInstitutionsMine
{
    /**
     * @param int[] $institutions
     */
    public function __construct(
        protected array $institutions
    ){
    }

    public function getInstitutions(): array
    {
        return $this->institutions;
    }
}

<?php

namespace App\Domain\Institution\Model;

class InstitutionDTO
{
    public function __construct(
        protected int $id
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}

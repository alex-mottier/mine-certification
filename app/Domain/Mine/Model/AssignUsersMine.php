<?php

namespace App\Domain\Mine\Model;

readonly class AssignUsersMine
{
    /**
     * @param int[] $users
     */
    public function __construct(
        protected array $users
    ){
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}

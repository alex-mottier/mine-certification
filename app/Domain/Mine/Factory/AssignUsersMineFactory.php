<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\Model\AssignUsersMine;

class AssignUsersMineFactory
{

    public function fromArray(array $data): AssignUsersMine
    {
        return new AssignUsersMine(
            users: $data
        );
    }
}

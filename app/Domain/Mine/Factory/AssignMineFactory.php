<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\Model\AssignMine;
use App\Http\Requests\Mine\AssignMineRequest;

class AssignMineFactory
{
    public function fromRequest(AssignMineRequest $request): AssignMine
    {
        return new AssignMine(
            certifiers: $request->validated('certifiers')
        );
    }
}

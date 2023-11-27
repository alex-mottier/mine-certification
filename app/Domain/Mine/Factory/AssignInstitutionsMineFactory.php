<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\Model\AssignInstitutionsMine;
use App\Http\Requests\Mine\AssignMineRequest;

class AssignInstitutionsMineFactory
{
    public function fromRequest(AssignMineRequest $request): AssignInstitutionsMine
    {
        return new AssignInstitutionsMine(
            institutions: $request->validated('owners')
        );
    }

    public function fromArray(array $institutions): AssignInstitutionsMine
    {
        return new AssignInstitutionsMine(
            institutions: $institutions
        );
    }
}

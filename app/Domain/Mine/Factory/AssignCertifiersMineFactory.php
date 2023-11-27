<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\Model\AssignCertifiersMine;
use App\Http\Requests\Mine\AssignMineRequest;

class AssignCertifiersMineFactory
{
    public function fromRequest(AssignMineRequest $request): AssignCertifiersMine
    {
        return new AssignCertifiersMine(
            certifiers: $request->validated('certifiers')
        );
    }

    public function fromArray(array $certifiers): AssignCertifiersMine
    {
        return new AssignCertifiersMine(
            certifiers: $certifiers
        );
    }
}

<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\Model\ValidateMine;
use App\Domain\Status\Status;
use App\Http\Requests\Mine\ValidateMineRequest;

class ValidateMineFactory
{

    public function fromRequest(ValidateMineRequest $request): ValidateMine
    {
        return new ValidateMine(
            status: Status::from($request->validated('status'))
        );
    }

    public function withStatus(Status $status): ValidateMine
    {
        return new ValidateMine(
            status: $status
        );
    }
}

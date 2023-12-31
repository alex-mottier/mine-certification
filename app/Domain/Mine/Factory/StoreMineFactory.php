<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\MineType;
use App\Domain\Mine\Model\StoreMine;
use App\Domain\Status\Status;
use App\Http\Requests\Mine\StoreMineRequest;

class StoreMineFactory
{

    public function fromRequest(StoreMineRequest $request): StoreMine
    {
        return new StoreMine(
            name: $request->validated('name'),
            email: $request->validated('email'),
            phoneNumber: $request->validated('phone_number'),
            taxNumber: $request->validated('tax_number'),
            longitude: $request->validated('longitude'),
            latitude: $request->validated('latitude'),
            status: Status::CREATED,
            type: MineType::from($request->validated('type')),
            imagePath: null,
        );
    }

    public function fromArray(array $array): StoreMine
    {
        return new StoreMine(
            name: $array['name'],
            email: $array['email'],
            phoneNumber: $array['phone_number'],
            taxNumber: $array['tax_number'],
            longitude: $array['longitude'],
            latitude: $array['latitude'],
            status: Status::CREATED,
            type: MineType::from($array['type']),
            imagePath: $array['image_path'],
        );
    }
}

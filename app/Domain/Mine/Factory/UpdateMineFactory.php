<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\MineType;
use App\Domain\Mine\Model\UpdateMine;
use App\Domain\Status\Status;

class UpdateMineFactory
{
    public function fromArray(array $array): UpdateMine
    {
        return new UpdateMine(
            mineId: $array['id'],
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

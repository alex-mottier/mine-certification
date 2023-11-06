<?php

namespace App\Domain\Mine\Factory;

use App\Domain\Mine\Model\MineDTO;
use App\Models\Mine;

class MineDTOFactory
{
    public function fromModel(Mine $mine): MineDTO
    {
        return new MineDTO(
            id: $mine->id,
            name: $mine->name,
            email: $mine->email,
            phoneNumber: $mine->phone_number,
            longitude: $mine->longitude,
            latitude: $mine->latitude,
            status: $mine->status
        );
    }
}

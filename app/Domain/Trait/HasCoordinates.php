<?php

namespace App\Domain\Trait;

use Illuminate\Database\Eloquent\Builder;

trait HasCoordinates
{
    const EARTH_RADIUS_KM = 6371;

    public function scopeInArea(Builder $query, float $longitude, float $latitude, float $radius): void
    {
        $query->selectRaw(
            '*, (? * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance',
            [
                self::EARTH_RADIUS_KM,
                $latitude,
                $longitude,
                $latitude
            ]
        )
            ->having('distance', '<', $radius);
    }
}

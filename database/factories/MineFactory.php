<?php

namespace Database\Factories;

use App\Domain\Mine\MineType;
use App\Domain\Status\Status;
use App\Domain\User\UserType;
use App\Models\Mine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mine>
 */
class MineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement([
            Status::FOR_VALIDATION,
            Status::REFUSED,
            Status::CREATED
        ]);
        $validatedBy = null;
        $validatedAt = null;
        if($status === Status::VALIDATED){
            $now = Carbon::now();
            $validatedBy = User::factory()->create([
                'status' => Status::VALIDATED,
                'type' => UserType::ADMINISTRATOR,
                'validated_at' => $now
            ]);
            $validatedAt = $now;
        }

        return array_filter([
            'name' => $this->faker->country ."'s mine ".$this->faker->randomDigit(),
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'tax_number' => $this->faker->swiftBicNumber,
            'status' => $status,
            'type' => $this->faker->randomElement(MineType::cases()),
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'image_path' => $this->faker->imageUrl(250,250, 'mine'),
            'created_by' => User::factory()->create(),
            'validated_by' => $validatedBy,
            'validated_at' => $validatedAt
        ]);
    }
}

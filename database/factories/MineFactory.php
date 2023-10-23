<?php

namespace Database\Factories;

use App\Domain\Status\Status;
use App\Models\Mine;
use App\Models\User;
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
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'tax_number' => $this->faker->swiftBicNumber,
            'status' => $this->faker->randomElement([Status::CREATED, Status::REFUSED, Status::FOR_VALIDATION]),
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'created_by' => User::factory()->create(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Domain\Status\Status;
use App\Domain\Type\ReportType;
use App\Models\Mine;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(10),
            'mine_id' => Mine::factory()->create(),
            'status' => Status::CREATED,
            'type' => $this->faker->randomElement(ReportType::cases())
        ];
    }
}

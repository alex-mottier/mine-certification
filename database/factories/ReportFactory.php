<?php

namespace Database\Factories;

use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use App\Domain\User\UserType;
use App\Models\Mine;
use App\Models\Report;
use App\Models\User;
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

        $status = $this->faker->randomElement(Status::class);
        $type = $this->faker->randomElement(ReportType::class);
        
        return [
            'name' => $this->faker->text(10),
            'mine_id' => Mine::factory()->create([
                'status' => Status::VALIDATED,
                'validated_by' => User::factory()->create([
                    'status' => Status::VALIDATED,
                    'type' => UserType::ADMINISTRATOR
                ])
            ]),
            'status' => $status,
            'type' => $type
        ];
    }
}

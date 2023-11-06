<?php

namespace Database\Factories;

use App\Domain\Status\Status;
use App\Domain\User\UserType;
use App\Models\CriteriaReport;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reaction>
 */
class ReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'criteria_report_id' => CriteriaReport::factory(),
            'user_id' => User::factory()->create([
                'status' => Status::VALIDATED,
                'type' => UserType::CERTIFIER
            ]),
            'status' => $this->faker->randomElement([Status::VALIDATED, Status::REFUSED]),
            'comment' => $this->faker->text()
        ];
    }
}

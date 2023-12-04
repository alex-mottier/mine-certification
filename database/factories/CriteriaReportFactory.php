<?php

namespace Database\Factories;

use App\Models\Criteria;
use App\Models\CriteriaReport;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CriteriaReport>
 */
class CriteriaReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'criteria_id' => Criteria::factory(),
            'report_id' => Report::factory(),
            'comment' => $this->faker->text,
            'score' => $this->faker->numberBetween(1, 10)
        ];
    }
}

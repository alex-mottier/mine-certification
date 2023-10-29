<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Criteria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Criteria>
 */
class CriteriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(25),
            'description' => $this->faker->text,
            'quota' => 1 / ($this->count != 0 ? $this->count : 1),
            'chapter_id' => Chapter::factory()
        ];
    }
}

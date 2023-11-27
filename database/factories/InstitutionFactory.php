<?php

namespace Database\Factories;

use App\Domain\Institution\InstitutionType;
use App\Domain\Status\Status;
use App\Domain\User\UserType;
use App\Models\Institution;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Institution>
 */
class InstitutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement([Status::FOR_VALIDATION, Status::VALIDATED, Status::REFUSED]);
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
            'name' => $this->faker->company,
            'description' => $this->faker->text,
            'status' => $status,
            'type' => $this->faker->randomElement(InstitutionType::cases()),
            'validated_by' => $validatedBy,
            'validated_at' => $validatedAt
        ]);
    }
}

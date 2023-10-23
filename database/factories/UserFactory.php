<?php

namespace Database\Factories;

use App\Domain\Status\Status;
use App\Domain\User\UserType;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
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
            Status::VALIDATED,
            Status::REFUSED,
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
            'username' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'status' => $status,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'type' => $this->faker->randomElement(UserType::cases()),
            'validated_by' => $validatedBy,
            'validated_at' => $validatedAt
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}

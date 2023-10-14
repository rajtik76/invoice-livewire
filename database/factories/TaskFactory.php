<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'contract_id' => Contract::factory(),
            'user_id' => User::factory(),
            'name' => 'WW-'.fake()->unique()->randomNumber(6, true).' '.fake()->slug(),
            'url' => fn () => fake()->boolean() ? fake()->url() : null,
            'note' => fn () => fake()->boolean() ? fake()->slug() : null,
            'active' => fn () => ! (fake()->numberBetween() < 100),
        ];
    }
}

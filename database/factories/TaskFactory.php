<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Contract;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory(),
            'contract_id' => fn (array $attributes) => Contract::factory()->create(['user_id' => $attributes['user_id']]),
            'name' => $this->faker->slug(),
            'url' => $this->faker->optional()->url(),
            'note' => $this->faker->optional()->sentence(),
            'active' => $this->faker->boolean(90),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskSpentTimeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'date' => fake()->dateTimeBetween('-3 months'),
            'time' => fake()->randomFloat(1, 1, 10),
            'note' => fake()->sentence(),
        ];
    }
}

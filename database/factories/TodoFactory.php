<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ['pending', 'open', 'in_progress', 'completed'];
        $priority = ['low', 'medium', 'high'];

        return [
            'title' => $this->faker->sentence(4),
            'assignee' => $this->faker->randomElement(['Alice', 'Bob', 'Charlie', 'Diana']),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'time_tracked' => $this->faker->randomFloat(1, 0, 10),
            'status' => $this->faker->randomElement($status),
            'priority' => $this->faker->randomElement($priority),
        ];
    }
}

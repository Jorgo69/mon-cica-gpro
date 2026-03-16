<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SpecificObjective>
 */
class SpecificObjectiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'logical_framework_id' => \App\Models\LogicalFramework::factory(),
            'description' => $this->faker->paragraph(),
        ];
    }
}

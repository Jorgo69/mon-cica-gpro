<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => \App\Models\Organization::factory(),
            'creator_user_id' => \App\Models\User::factory(),
            'project_type_id' => \App\Models\ProjectType::factory(),
            'project_code' => $this->faker->unique()->bothify('PRJ-####'),
            'title' => $this->faker->sentence(),
            'status' => \App\Enums\ProjectStatus::DRAFT,
        ];
    }
}

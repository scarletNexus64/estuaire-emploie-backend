<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramStep>
 */
class ProgramStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resourceTypes = ['link', 'document', 'video', 'article'];

        // Generate random resources
        $resources = [];
        $numResources = $this->faker->numberBetween(0, 3);
        for ($i = 0; $i < $numResources; $i++) {
            $resources[] = [
                'title' => $this->faker->sentence(3),
                'url' => $this->faker->url(),
                'type' => $this->faker->randomElement($resourceTypes),
            ];
        }

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'content' => $this->faker->paragraphs(3, true),
            'resources' => count($resources) > 0 ? $resources : null,
            'order' => $this->faker->numberBetween(1, 20),
            'estimated_duration_days' => $this->faker->numberBetween(1, 14),
            'is_required' => $this->faker->boolean(70),
        ];
    }
}

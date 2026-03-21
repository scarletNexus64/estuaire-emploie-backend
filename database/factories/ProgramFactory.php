<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['immersion_professionnelle', 'entreprenariat', 'transformation_professionnelle'];
        $icons = ['📚', '🎓', '💼', '🌟', '🚀', '💡', '🎯', '📈'];

        $type = $this->faker->randomElement($types);

        return [
            'title' => $this->faker->sentence(4),
            'slug' => $this->faker->unique()->slug(),
            'type' => $type,
            'description' => $this->faker->paragraph(3),
            'objectives' => implode("\n", $this->faker->sentences(5)),
            'icon' => $this->faker->randomElement($icons),
            'duration_weeks' => $this->faker->numberBetween(4, 24),
            'order' => $this->faker->numberBetween(0, 10),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}

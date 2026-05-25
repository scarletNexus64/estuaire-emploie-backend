<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Portfolio>
 */
class PortfolioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $templates = ['professional', 'creative', 'tech'];
        $themeColors = ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4'];

        return [
            'title' => fake()->jobTitle(),
            'bio' => fake()->paragraph(3),
            'template_id' => fake()->randomElement($templates),
            'theme_color' => fake()->randomElement($themeColors),
            'is_public' => fake()->boolean(80), // 80% chance of being public
            'skills' => $this->generateSkills(),
            'experiences' => $this->generateExperiences(),
            'education' => $this->generateEducation(),
            'projects' => $this->generateProjects(),
            'certifications' => $this->generateCertifications(),
            'languages' => $this->generateLanguages(),
            'social_links' => $this->generateSocialLinks(),
        ];
    }

    private function generateSkills(): array
    {
        $skills = [];
        $skillNames = ['PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'MySQL', 'PostgreSQL', 'Docker', 'Git', 'API REST'];
        $levels = ['Débutant', 'Intermédiaire', 'Avancé', 'Expert'];

        $count = rand(3, 8);
        for ($i = 0; $i < $count; $i++) {
            $skills[] = [
                'name' => fake()->randomElement($skillNames),
                'level' => fake()->randomElement($levels),
            ];
        }

        return $skills;
    }

    private function generateExperiences(): array
    {
        $experiences = [];
        $count = rand(1, 4);

        for ($i = 0; $i < $count; $i++) {
            $experiences[] = [
                'title' => fake()->jobTitle(),
                'company' => fake()->company(),
                'duration' => rand(2020, 2024) . ' - ' . (rand(0, 1) ? 'Présent' : rand(2021, 2025)),
                'description' => fake()->paragraph(2),
            ];
        }

        return $experiences;
    }

    private function generateEducation(): array
    {
        $education = [];
        $count = rand(1, 3);

        for ($i = 0; $i < $count; $i++) {
            $education[] = [
                'degree' => fake()->randomElement(['Licence en Informatique', 'Master en Génie Logiciel', 'Doctorat en IA', 'BTS Informatique']),
                'school' => fake()->randomElement(['Université de Douala', 'Université de Yaoundé I', 'École Polytechnique', 'ISI Douala']),
                'year' => rand(2015, 2023),
                'description' => fake()->paragraph(),
            ];
        }

        return $education;
    }

    private function generateProjects(): array
    {
        $projects = [];
        $count = rand(2, 5);

        for ($i = 0; $i < $count; $i++) {
            $projects[] = [
                'name' => fake()->catchPhrase(),
                'description' => fake()->paragraph(),
                'url' => fake()->url(),
            ];
        }

        return $projects;
    }

    private function generateCertifications(): array
    {
        $certifications = [];
        $count = rand(0, 3);

        for ($i = 0; $i < $count; $i++) {
            $certifications[] = [
                'name' => fake()->randomElement(['AWS Certified Developer', 'Google Cloud Professional', 'Oracle Java Certification', 'Scrum Master']),
                'issuer' => fake()->randomElement(['Amazon Web Services', 'Google', 'Oracle', 'Scrum Alliance']),
                'date' => fake()->date(),
                'credential_url' => fake()->url(),
            ];
        }

        return $certifications;
    }

    private function generateLanguages(): array
    {
        return [
            ['language' => 'Français', 'level' => 'Natif'],
            ['language' => 'Anglais', 'level' => fake()->randomElement(['Intermédiaire', 'Avancé', 'Courant'])],
        ];
    }

    private function generateSocialLinks(): array
    {
        return [
            'github' => fake()->boolean(70) ? 'https://github.com/' . fake()->userName() : null,
            'linkedin' => fake()->boolean(80) ? 'https://linkedin.com/in/' . fake()->userName() : null,
            'twitter' => fake()->boolean(40) ? 'https://twitter.com/' . fake()->userName() : null,
            'website' => fake()->boolean(50) ? fake()->url() : null,
        ];
    }
}

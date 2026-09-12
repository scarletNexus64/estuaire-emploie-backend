<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Mot de passe hashé une seule fois et réutilisé par toutes les instances.
     */
    protected static ?string $password = null;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->numerify('6########'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'candidate',
            'locale' => 'fr',
            'country' => 'CM',
            'preferred_currency' => 'XAF',
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * L'adresse e-mail ne doit pas être vérifiée.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function candidate(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'candidate']);
    }

    public function recruiter(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'recruiter']);
    }

    public function student(): static
    {
        return $this->state(fn (array $attributes) => ['role' => 'student']);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'is_super_admin' => true,
        ]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'prenom' => $this->faker->firstName(),
            'nom' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'), // mot de passe par défaut
            'typeCompte' => $this->faker->randomElement(['prestataire', 'client']),
            'adresse' => $this->faker->address(),
            'longitude' => $this->faker->longitude( -5.0, 2.5),  // zone Afrique de l’Ouest
            'latitude' => $this->faker->latitude( 9.0, 15.0),
            'numero' => $this->faker->phoneNumber(),
            'remember_token' => Str::random(10),
            'descriptions' => $this->faker->sentence(10),
            'profile' => $this->faker->imageUrl(200, 200, 'people'), // image aléatoire
            'status' => $this->faker->randomElement([0, 1]),
            'profession' => $this->faker->jobTitle,
            'boosted' => $this->faker->randomElement([true, false]),

        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Prestataire;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PrestataireSeeder extends Seeder
{
    public function run()
    {
        \Faker\Factory::create('fr_FR')->unique();

        for ($i = 0; $i < 20; $i++) {
            Prestataire::create([
                'nom' => fake()->lastName,
                'prenom' => fake()->firstName,
                'adresse' => fake()->address,
                'longitude' => fake()->longitude,
                'latitude' => fake()->latitude,
                'profession' => fake()->randomElement(['maçon', 'plombier', 'électricien', 'menuisier', 'soudeur']),
                'numero' => fake()->unique()->phoneNumber,
                'profile' => 'default.png', // ou fake()->imageUrl()
                'whatsapp' => fake()->phoneNumber,
                'boosted' => fake()->boolean(30), // 30% de chance d'être true
                'token' => Str::random(60),
                'email'=> fake()->email,
            ]);
        }
    }
}

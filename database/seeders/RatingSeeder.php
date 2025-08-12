<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Prestataire;
use App\Models\rating;
class RatingSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $prestataires = Prestataire::all();

        // Chaque utilisateur donne une note à quelques prestataires au hasard
        foreach ($users as $user) {
            // Chaque user va noter entre 1 et 5 prestataires
            $ratedPrestataires = $prestataires->random(rand(1, 5));

            foreach ($ratedPrestataires as $prestataire) {
                rating::create([
                    'users_id' => $user->id,
                    'prestataires_id' => $prestataire->id,
                    'notes' => rand(1, 5),
                ]);
            }
        }
    }
}

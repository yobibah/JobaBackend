<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Horaire;
use App\Models\horaires;
use App\Models\Prestataire;

class HoraireSeeder extends Seeder
{
    public function run()
    {
        $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

        foreach (Prestataire::all() as $prestataire) {
            // Donne entre 3 et 6 jours d'ouverture au hasard
            $joursOuvrables = collect($jours)->shuffle()->take(rand(3, 6));

            foreach ($joursOuvrables as $jour) {
                horaires::create([
                    'id_prestataires' => $prestataire->id,
                    'horaire_ouv' => fake()->randomElement([
                        '08:00-12:00', '09:00-13:00', '14:00-18:00', '08:30-17:30'
                    ]),
                    'jour_ouv' => $jour,
                ]);
            }
        }
    }
}


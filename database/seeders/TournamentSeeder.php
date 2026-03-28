<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = DB::table('users')->where('email', 'organisateur@youcode.ma')->first();

    if (!$organizer) {
        $organizer = DB::table('users')->first();
    }

    if (!$organizer) {
        $this->command->error("Erreur : Aucun utilisateur trouvé. Lancez d'abord le UserSeeder !");
        return;
    }

    $tournaments = [
        [
            'organizer_id' => $organizer->id,
            'game_id' => 3, 
                'category_id' => 2, 
                'title' => 'Tournoi Babyfoot Inter-Promos',
                'status' => 'Ouvertes',
                'max_capacity' => 16,
                'event_date' => Carbon::now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer_id' => $organizer->id,
                'game_id' => 1, 
                'category_id' => 1, 
                'title' => 'Safi LoL Championship',
                'status' => 'À venir',
                'max_capacity' => 32,
                'event_date' => Carbon::now()->addDays(14),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer_id' => $organizer->id,
                'game_id' => 5, 
                'category_id' => 3, 
                'title' => 'Hackathon Laravel 48h',
                'status' => 'Terminées',
                'max_capacity' => 20,
                'event_date' => Carbon::now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('tournaments')->insert($tournaments);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $password = Hash::make('password123'); // Mot de passe universel

        // ---------------------------------------------------
        // 1. RÔLES, CATÉGORIES & JEUX
        // ---------------------------------------------------
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Organisateur', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Jury', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Compétiteur', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('categories')->insert([
    ['name' => 'League of Legends', 'created_at' => $now, 'updated_at' => $now],
    ['name' => 'Valorant', 'created_at' => $now, 'updated_at' => $now],
    ['name' => 'Babyfoot', 'created_at' => $now, 'updated_at' => $now],
    ['name' => 'Chess (Échecs)', 'created_at' => $now, 'updated_at' => $now],
    ['name' => 'Ping-Pong', 'created_at' => $now, 'updated_at' => $now],
    ['name' => 'Billiard', 'created_at' => $now, 'updated_at' => $now],
    ['name' => 'FIFA', 'created_at' => $now, 'updated_at' => $now],
    ['name' => 'Football', 'created_at' => $now, 'updated_at' => $now],
]);

        DB::table('games')->insert([
            ['id' => 1, 'name' => 'League of Legends', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Valorant', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Babyfoot', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Chess (Échecs)', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Ping-Pong', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'Billiard', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => 'FIFA', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'name' => 'Football', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ---------------------------------------------------
        // 2. UTILISATEURS (Admin, 7 Orgas, Compétiteurs)
        // ---------------------------------------------------
        
        // Admin
        $adminId = DB::table('users')->insertGetId(['username' => 'GodModeAdmin', 'email' => 'admin@youcode.ma', 'password' => $password, 'created_at' => $now, 'updated_at' => $now]);
        DB::table('user_roles')->insert(['user_id' => $adminId, 'role_id' => 1, 'assigned_at' => $now]);

        // 7 Organisateurs distincts (1 pour chaque tournoi)
        $organizerIds = [];
        for ($i = 1; $i <= 7; $i++) {
            $orgId = DB::table('users')->insertGetId([
                'username' => 'Orga_Master_' . $i, 
                'email' => 'orga' . $i . '@youcode.ma', 
                'password' => $password, 
                'created_at' => $now, 'updated_at' => $now
            ]);
            DB::table('user_roles')->insert(['user_id' => $orgId, 'role_id' => 2, 'assigned_at' => $now]);
            $organizerIds[] = $orgId;
        }

        // 40 Compétiteurs
        $competitorIds = [];
        for ($i = 1; $i <= 40; $i++) {
            $userId = DB::table('users')->insertGetId([
                'username' => 'Player_' . rand(1000, 9999),
                'email' => 'player' . $i . '@youcode.ma',
                'password' => $password,
                'created_at' => $now, 'updated_at' => $now
            ]);
            $competitorIds[] = $userId;
            DB::table('user_roles')->insert(['user_id' => $userId, 'role_id' => 4, 'assigned_at' => $now]);
            DB::table('competitor_profiles')->insert(['user_id' => $userId, 'games_won' => rand(0, 10), 'games_loss' => rand(0, 5), 'created_at' => $now, 'updated_at' => $now]);
        }

        // Ton compte de test
        $myId = DB::table('users')->insertGetId(['username' => 'Oussama_Pro', 'email' => 'oussama@youcode.ma', 'password' => $password, 'created_at' => $now, 'updated_at' => $now]);
        $competitorIds[] = $myId;
        DB::table('user_roles')->insert(['user_id' => $myId, 'role_id' => 4, 'assigned_at' => $now]);
        DB::table('competitor_profiles')->insert(['user_id' => $myId, 'games_won' => 15, 'games_loss' => 2, 'created_at' => $now, 'updated_at' => $now]);

        // ---------------------------------------------------
        // 3. TOURNOIS (7 Tournois assignés en 1-to-1)
        // ---------------------------------------------------
        $tournamentsData = [
            ['title' => 'Winter Cup YouCode 2026', 'game_id' => 7, 'category_id' => 1, 'status' => 'Terminé'],
            ['title' => 'LoL Championship', 'game_id' => 1, 'category_id' => 1, 'status' => 'En cours'],
            ['title' => 'Valorant Arena', 'game_id' => 2, 'category_id' => 1, 'status' => 'À venir'],
            ['title' => 'Tournoi Babyfoot YC', 'game_id' => 3, 'category_id' => 2, 'status' => 'En cours'],
            ['title' => 'Hackathon GreenTech', 'game_id' => 5, 'category_id' => 3, 'status' => 'À venir'],
            ['title' => 'Master Chess', 'game_id' => 4, 'category_id' => 2, 'status' => 'Terminé'],
            ['title' => 'Ping-Pong Clash', 'game_id' => 6, 'category_id' => 2, 'status' => 'À venir'],
        ];

        $tournaments = [];
        foreach ($tournamentsData as $index => $data) {
            $tId = DB::table('tournaments')->insertGetId([
                'organizer_id' => $organizerIds[$index], // L'Organisateur 1 a le Tournoi 1, le 2 a le 2, etc.
                'game_id' => $data['game_id'],
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'status' => $data['status'],
                'max_capacity' => 16,
                'event_date' => Carbon::now()->addDays(rand(-10, 30)),
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $tournaments[] = ['id' => $tId, 'status' => $data['status']];
        }

        // ---------------------------------------------------
        // 4. ÉQUIPES, INSCRIPTIONS & MATCHS
        // ---------------------------------------------------
        $teamNames = ['Titans', 'Cyber Ninjas', 'Les Loups Gris', 'Alpha Squad', 'Bravo Team', 'Delta Force', 'Ghost Riders', 'Phoenix'];

        foreach ($tournaments as $t) {
            $teamsInThisTournament = [];
            $numTeams = rand(4, 8);
            
            for ($i = 0; $i < $numTeams; $i++) {
                $teamId = DB::table('teams')->insertGetId([
                    'tournament_id' => $t['id'],
                    'name' => $teamNames[$i] . ' ' . rand(1, 99),
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                $teamsInThisTournament[] = $teamId;

                $p1 = $competitorIds[array_rand($competitorIds)];
                $p2 = $competitorIds[array_rand($competitorIds)];

                DB::table('registrations')->insert([
                    ['user_id' => $p1, 'tournament_id' => $t['id'], 'registration_date' => $now, 'status' => 'Confirmé', 'created_at' => $now, 'updated_at' => $now],
                    ['user_id' => $p2, 'tournament_id' => $t['id'], 'registration_date' => $now, 'status' => 'Confirmé', 'created_at' => $now, 'updated_at' => $now]
                ]);

                DB::table('team_members')->insert([
                    ['team_id' => $teamId, 'user_id' => $p1, 'joined_at' => $now, 'created_at' => $now, 'updated_at' => $now],
                    ['team_id' => $teamId, 'user_id' => $p2, 'joined_at' => $now, 'created_at' => $now, 'updated_at' => $now]
                ]);
            }

            if (count($teamsInThisTournament) >= 2) {
                for ($m = 0; $m < count($teamsInThisTournament) - 1; $m += 2) {
                    $team1 = $teamsInThisTournament[$m];
                    $team2 = $teamsInThisTournament[$m+1];
                    
                    $isFinished = ($t['status'] === 'Terminé' || rand(0, 1) == 1);
                    $score1 = $isFinished ? rand(0, 5) : null;
                    $score2 = $isFinished ? rand(0, 5) : null;
                    
                    if ($isFinished && $score1 == $score2) $score1++; 
                    $winner = $isFinished ? (($score1 > $score2) ? $team1 : $team2) : null;

                    DB::table('matches')->insert([
                        'tournament_id' => $t['id'],
                        'team1_id' => $team1,
                        'team2_id' => $team2,
                        'winner_team_id' => $winner,
                        'score' => $isFinished ? "$score1 - $score2" : null,
                        'status' => $isFinished ? 'Terminé' : 'Programmé',
                        'played_at' => $isFinished ? Carbon::now()->subDays(rand(1,5)) : Carbon::now()->addDays(rand(1,5)),
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
                }
            }
        }
    }
}
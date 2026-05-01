<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\CarbonImmutable;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = CarbonImmutable::now();
        $password = Hash::make('password123'); // Mot de passe universel

        // ---------------------------------------------------
        // 1. RÔLES, CATÉGORIES & JEUX
        // ---------------------------------------------------
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Organisateur', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Compétiteur', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'MOBA', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'FPS', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Sports de table', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Jeux de société', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Simulation Sportive', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'Sports en plein air', 'created_at' => $now, 'updated_at' => $now],
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
        // 2. UTILISATEURS (Admin, 7 Orgas, 100 Compétiteurs)
        // ---------------------------------------------------
        
        // Admin
        $adminId = DB::table('users')->insertGetId(['username' => 'Admin', 'email' => 'admin@youcode.ma', 'password' => $password, 'created_at' => $now, 'updated_at' => $now]);
        DB::table('user_roles')->insert(['user_id' => $adminId, 'role_id' => 1, 'assigned_at' => $now]);

        // 7 Organisateurs distincts
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

        // 100 Compétiteurs avec des statistiques plus larges
        $competitorIds = [];
        for ($i = 1; $i <= 100; $i++) {
            $userId = DB::table('users')->insertGetId([
                'username' => 'Player_' . rand(1000, 99999),
                'email' => 'player' . $i . '@youcode.ma',
                'password' => $password,
                'created_at' => $now->subDays(rand(1, 100)), // Simulate staggered registrations
                'updated_at' => $now
            ]);
            $competitorIds[] = $userId;
            DB::table('user_roles')->insert(['user_id' => $userId, 'role_id' => 3, 'assigned_at' => $now]);
            DB::table('competitor_profiles')->insert([
                'user_id' => $userId, 
                'games_won' => rand(0, 45), 
                'games_loss' => rand(0, 30), 
                'created_at' => $now, 
                'updated_at' => $now
            ]);
        }

        // Ton compte de test
        $myId = DB::table('users')->insertGetId(['username' => 'Oussama_Pro', 'email' => 'oussama@youcode.ma', 'password' => $password, 'created_at' => $now, 'updated_at' => $now]);
        $competitorIds[] = $myId;
        DB::table('user_roles')->insert(['user_id' => $myId, 'role_id' => 3, 'assigned_at' => $now]);
        DB::table('competitor_profiles')->insert(['user_id' => $myId, 'games_won' => 50, 'games_loss' => 5, 'created_at' => $now, 'updated_at' => $now]);

        // ---------------------------------------------------
        // 3. TOURNOIS (15 Tournois pour de meilleures requêtes)
        // ---------------------------------------------------
        $tournamentsData = [
            ['title' => 'Winter Cup YouCode 2025', 'game_id' => 7, 'category_id' => 5, 'status' => 'Terminé', 'offset' => -60],
            ['title' => 'LoL Championship', 'game_id' => 1, 'category_id' => 1, 'status' => 'Ouvert', 'offset' => 30],
            ['title' => 'Valorant Arena', 'game_id' => 2, 'category_id' => 2, 'status' => 'À venir', 'offset' => 15],
            ['title' => 'Tournoi Babyfoot YC', 'game_id' => 3, 'category_id' => 3, 'status' => 'Ouvert', 'offset' => 25],
            ['title' => 'Ping-Pong Clash', 'game_id' => 5, 'category_id' => 3, 'status' => 'À venir', 'offset' => 20],
            ['title' => 'Master Chess', 'game_id' => 4, 'category_id' => 4, 'status' => 'Terminé', 'offset' => -30],
            ['title' => 'FIFA Summer League', 'game_id' => 7, 'category_id' => 5, 'status' => 'Fermé', 'offset' => -10],
            ['title' => 'Football Outdoor Cup', 'game_id' => 8, 'category_id' => 6, 'status' => 'Terminé', 'offset' => -90],
            ['title' => 'YouCode Billiard Night', 'game_id' => 6, 'category_id' => 3, 'status' => 'Terminé', 'offset' => -5],
            ['title' => 'LoL Spring Split', 'game_id' => 1, 'category_id' => 1, 'status' => 'À venir', 'offset' => 45],
            ['title' => 'Valorant Pro Draft', 'game_id' => 2, 'category_id' => 2, 'status' => 'Terminé', 'offset' => -120],
            ['title' => 'Chess Blitz Weekly', 'game_id' => 4, 'category_id' => 4, 'status' => 'Fermé', 'offset' => 0],
            ['title' => 'Babyfoot All-Stars', 'game_id' => 3, 'category_id' => 3, 'status' => 'À venir', 'offset' => 10],
            ['title' => 'Ping-Pong Duo', 'game_id' => 5, 'category_id' => 3, 'status' => 'Terminé', 'offset' => -15],
            ['title' => 'FIFA World Cup YC', 'game_id' => 7, 'category_id' => 5, 'status' => 'À venir', 'offset' => 60],
        ];

        $tournaments = [];
        foreach ($tournamentsData as $index => $data) {
            $maxCapacity = rand(24, 64);

            $tId = DB::table('tournaments')->insertGetId([
                'organizer_id' => $organizerIds[$index % count($organizerIds)], // Répartir sur les 7 orgas
                'game_id' => $data['game_id'],
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'status' => $data['status'],
                'max_capacity' => $maxCapacity,
                'event_date' => $now->addDays($data['offset']),
                'created_at' => $now->subDays(rand(10, 50)),
                'updated_at' => $now
            ]);
            $tournaments[] = [
                'id' => $tId,
                'status' => $data['status'],
                'offset' => $data['offset'],
                'max_capacity' => $maxCapacity,
            ];
        }

        // ---------------------------------------------------
        // 4. ÉQUIPES, INSCRIPTIONS & MATCHS
        // ---------------------------------------------------
        $teamNames = ['Titans', 'Cyber Ninjas', 'Les Loups Gris', 'Alpha Squad', 'Bravo Team', 'Delta Force', 'Ghost Riders', 'Phoenix', 'Vipers', 'Cobras', 'Eagles', 'Falcons', 'Dragons', 'Spartans', 'Gladiators', 'Wizards'];

        foreach ($tournaments as $t) {
            // Skip matches for cancelled tournaments
            if ($t['status'] === 'Fermé') continue;

            $teamsInThisTournament = [];
            $maxTeamsByCapacity = max(1, (int) floor($t['max_capacity'] / 2));
            $numTeams = rand(4, min(16, $maxTeamsByCapacity));
            $availableCompetitors = $competitorIds;
            shuffle($availableCompetitors);
            
            for ($i = 0; $i < $numTeams; $i++) {
                if (count($availableCompetitors) < 2) {
                    break;
                }

                $teamId = DB::table('teams')->insertGetId([
                    'tournament_id' => $t['id'],
                    'name' => $teamNames[$i % count($teamNames)] . ' ' . rand(100, 999),
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                $teamsInThisTournament[] = $teamId;

                // 2 joueurs par équipe (pour simplifier)
                $p1 = array_pop($availableCompetitors);
                $p2 = array_pop($availableCompetitors);

                DB::table('registrations')->insert([
                    ['user_id' => $p1, 'tournament_id' => $t['id'], 'team_id' => $teamId, 'registration_date' => $now, 'status' => 'Confirmé', 'created_at' => $now, 'updated_at' => $now],
                    ['user_id' => $p2, 'tournament_id' => $t['id'], 'team_id' => $teamId, 'registration_date' => $now, 'status' => 'Confirmé', 'created_at' => $now, 'updated_at' => $now]
                ]);

                DB::table('team_members')->insert([
                    ['team_id' => $teamId, 'user_id' => $p1, 'joined_at' => $now, 'created_at' => $now, 'updated_at' => $now],
                    ['team_id' => $teamId, 'user_id' => $p2, 'joined_at' => $now, 'created_at' => $now, 'updated_at' => $now]
                ]);
            }

            // Génération de matchs (Phase de poule ou bracket basique)
            if (count($teamsInThisTournament) >= 2) {
                // Créer environ 3 à 8 matchs par tournoi
                $numMatches = rand(3, floor(count($teamsInThisTournament) / 2) + 2); 
                
                for ($m = 0; $m < $numMatches; $m++) {
                    // Pick 2 random unique teams
                    $team1 = $teamsInThisTournament[array_rand($teamsInThisTournament)];
                    $team2 = $teamsInThisTournament[array_rand($teamsInThisTournament)];
                    while ($team1 === $team2) {
                        $team2 = $teamsInThisTournament[array_rand($teamsInThisTournament)];
                    }
                    
                    $isFinished = ($t['status'] === 'Terminé');
                    
                    if ($isFinished) {
                        $score1 = rand(0, 5);
                        $score2 = rand(0, 5);
                        // Eviter les égalités la plupart du temps, mais en laisser quelques-unes si c'est du foot/échecs
                        if ($score1 == $score2 && rand(0,10) > 2) $score1++; 
                        
                        if ($score1 > $score2) $winner = $team1;
                        elseif ($score2 > $score1) $winner = $team2;
                        else $winner = null; // Match nul
                        
                        $matchStatus = 'Terminé';
                        $playedAt = $now->addDays($t['offset'])->subHours(rand(1, 48));
                    } else {
                        $score1 = null;
                        $score2 = null;
                        $winner = null;
                        $matchStatus = 'Programmé';
                        $playedAt = $now->addDays($t['offset'])->addHours(rand(1, 48));
                    }

                    DB::table('matches')->insert([
                        'tournament_id' => $t['id'],
                        'team1_id' => $team1,
                        'team2_id' => $team2,
                        'winner_team_id' => $winner,
                        'score' => $isFinished ? "$score1 - $score2" : null,
                        'status' => $matchStatus,
                        'played_at' => $playedAt,
                        'created_at' => $now->subDays(rand(1, 5)),
                        'updated_at' => $now
                    ]);
                }
            }
        }
    }
}

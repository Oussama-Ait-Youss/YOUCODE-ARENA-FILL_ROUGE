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
        $password = Hash::make('password123');

        // ---------------------------------------------------
        // ROLES
        // ---------------------------------------------------
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Organisateur', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Compétiteur', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // ---------------------------------------------------
        // CATEGORIES
        // ---------------------------------------------------
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'MOBA'],
            ['id' => 2, 'name' => 'FPS'],
            ['id' => 3, 'name' => 'Sports de table'],
            ['id' => 4, 'name' => 'Jeux de société'],
            ['id' => 5, 'name' => 'Simulation Sportive'],
            ['id' => 6, 'name' => 'Sports en plein air'],
        ]);

        // ---------------------------------------------------
        // GAMES
        // ---------------------------------------------------
        DB::table('games')->insert([
            ['id' => 1, 'name' => 'League of Legends'],
            ['id' => 2, 'name' => 'Valorant'],
            ['id' => 3, 'name' => 'Babyfoot'],
            ['id' => 4, 'name' => 'Chess'],
            ['id' => 5, 'name' => 'Ping-Pong'],
            ['id' => 6, 'name' => 'Billiard'],
            ['id' => 7, 'name' => 'FIFA'],
            ['id' => 8, 'name' => 'Football'],
        ]);

        // ---------------------------------------------------
        // ADMIN
        // ---------------------------------------------------
        $adminId = DB::table('users')->insertGetId([
            'username' => 'Admin',
            'email' => 'admin@youcode.ma',
            'password' => $password,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $adminId,
            'role_id' => 1,
            'assigned_at' => $now
        ]);

        // ---------------------------------------------------
        // COMPETITORS
        // ---------------------------------------------------
        $competitors = [];

        for ($i = 1; $i <= 200; $i++) {

            $userId = DB::table('users')->insertGetId([
                'username' => "Player_$i",
                'email' => "player$i@youcode.ma",
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            $competitors[] = $userId;

            DB::table('user_roles')->insert([
                'user_id' => $userId,
                'role_id' => 3,
                'assigned_at' => $now
            ]);

            DB::table('competitor_profiles')->insert([
                'user_id' => $userId,
                'games_won' => rand(0, 50),
                'games_loss' => rand(0, 40),
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // ---------------------------------------------------
        // TOURNAMENTS
        // ---------------------------------------------------
        $tournaments = [];

        for ($i = 1; $i <= 20; $i++) {

            $tournaments[] = DB::table('tournaments')->insertGetId([
                'organizer_id' => $adminId,
                'game_id' => rand(1, 8),
                'category_id' => rand(1, 6),
                'title' => "Tournament $i",
                'status' => ['À venir', 'En cours', 'Terminé'][rand(0, 2)],
                'max_capacity' => rand(16, 64),
                'event_date' => $now->addDays(rand(-30, 30)),
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // ---------------------------------------------------
        // TEAMS + MEMBERS + REGISTRATIONS (FIXED)
        // ---------------------------------------------------
        $teamNames = ['Alpha', 'Beta', 'Omega', 'Delta', 'Titan', 'Phoenix', 'Storm', 'Vipers', 'Dragons', 'Wolves'];

        foreach ($tournaments as $tournamentId) {

            $teams = [];

            // ✅ unique players per tournament
            $availablePlayers = $competitors;
            shuffle($availablePlayers);

            $teamCount = rand(8, 12);

            for ($i = 0; $i < $teamCount; $i++) {

                $teamId = DB::table('teams')->insertGetId([
                    'tournament_id' => $tournamentId,
                    'name' => $teamNames[array_rand($teamNames)] . "_$i",
                    'created_at' => $now,
                    'updated_at' => $now
                ]);

                $teams[] = $teamId;

                $playersPerTeam = rand(3, 5);

                for ($j = 0; $j < $playersPerTeam; $j++) {

                    if (empty($availablePlayers)) break;

                    $userId = array_pop($availablePlayers);

                    DB::table('team_members')->insert([
                        'team_id' => $teamId,
                        'user_id' => $userId,
                        'joined_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

                    DB::table('registrations')->insert([
                        'user_id' => $userId,
                        'tournament_id' => $tournamentId,
                        'team_id' => $teamId,
                        'status' => 'Confirmé',
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
                }
            }

            // ---------------------------------------------------
            // MATCHES
            // ---------------------------------------------------
            for ($i = 0; $i < 20; $i++) {

                $team1 = $teams[array_rand($teams)];
                $team2 = $teams[array_rand($teams)];

                while ($team1 === $team2) {
                    $team2 = $teams[array_rand($teams)];
                }

                $score1 = rand(0, 5);
                $score2 = rand(0, 5);

                $winner = null;
                $status = 'Programmé';

                if ($score1 !== $score2) {
                    $winner = $score1 > $score2 ? $team1 : $team2;
                    $status = 'Terminé';
                } else {
                    $status = 'Draw';
                }

                DB::table('matches')->insert([
                    'tournament_id' => $tournamentId,
                    'team1_id' => $team1,
                    'team2_id' => $team2,
                    'winner_team_id' => $winner,
                    'score' => "$score1 - $score2",
                    'status' => $status,
                    'played_at' => $now->addDays(rand(-10, 10)),
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }
    }
}
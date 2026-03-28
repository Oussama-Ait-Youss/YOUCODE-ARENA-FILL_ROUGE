<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class RolesAndCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Organisateur', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jury', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Compétiteur', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('roles')->insert($roles);

        $categories = [
            ['name' => 'e-sport', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sport Physique', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hackathon', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('categories')->insert($categories);

        $games = [
            ['name' => 'League of Legends', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Valorant', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Babyfoot', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chess (Échecs)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hackathon Web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ping-Pong', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('games')->insert($games);
    }
}

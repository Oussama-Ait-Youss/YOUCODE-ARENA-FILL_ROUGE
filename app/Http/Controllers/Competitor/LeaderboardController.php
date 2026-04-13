<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        
        $leaders = User::join('competitor_profiles', 'users.id', '=', 'competitor_profiles.user_id')
            ->orderBy('competitor_profiles.games_won', 'desc')
            ->select('users.id', 'users.username', 'competitor_profiles.games_won', 'competitor_profiles.games_loss')
            ->take(10)
            ->get();

        return view('competitor.leaderboard', compact('leaders'));
    }
}

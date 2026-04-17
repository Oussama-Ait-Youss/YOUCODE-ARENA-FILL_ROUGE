<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Matchh;
use App\Models\Tournament;
use Illuminate\Contracts\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();

        $user->loadMissing(['teams.tournament.game', 'competitorProfile']);

        $teamIds = $user->teams->pluck('id');

        $matches = Matchh::with(['tournament.game', 'team1', 'team2'])
            ->where(function ($query) use ($teamIds) {
                $query->whereIn('team1_id', $teamIds)
                    ->orWhereIn('team2_id', $teamIds);
            })
            ->latest('played_at')
            ->get();

        $wins = $matches->where('winner_team_id', '!=', null)
            ->whereIn('winner_team_id', $teamIds)
            ->count();

        $losses = $matches->where('status', 'Terminé')
            ->whereNotNull('winner_team_id')
            ->whereNotIn('winner_team_id', $teamIds)
            ->count();

        $playedMatches = $wins + $losses;
        $winRate = $playedMatches > 0 ? (int) round(($wins / $playedMatches) * 100) : 0;

        $currentTournamentIds = $user->teams
            ->pluck('tournament_id')
            ->unique()
            ->values();

        $myTournaments = Tournament::with(['game', 'matches.team1', 'matches.team2'])
            ->whereIn('id', $currentTournamentIds)
            ->latest('event_date')
            ->get();

        $upcomingChallenges = $matches
            ->whereIn('status', ['Programmé', 'En attente'])
            ->sortBy('played_at')
            ->take(6)
            ->values();

        $stats = [
            'wins' => $wins,
            'losses' => $losses,
            'played_matches' => $playedMatches,
            'win_rate' => $winRate,
            'active_tournaments' => $myTournaments->count(),
            'challenge_cards' => $upcomingChallenges->count(),
        ];

        return view('competitor.profile', compact(
            'user',
            'myTournaments',
            'stats',
            'upcomingChallenges'
        ));
    }
}

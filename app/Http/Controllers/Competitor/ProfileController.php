<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Matchh;
use App\Models\Registration;
use App\Models\Tournament;
use Illuminate\Contracts\View\View;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load(['competitorProfile', 'teams']);

        $pendingRegistrations = $user->registrations()
            ->where('status', 'En attente')
            ->with(['tournament', 'team'])
            ->get();

        $pendingInvites = $pendingRegistrations
            ->filter(fn (Registration $registration) => $registration->team_id && !$user->teams->contains('id', $registration->team_id))
            ->values();

        $pendingApprovals = $pendingRegistrations
            ->reject(fn (Registration $registration) => $pendingInvites->contains('id', $registration->id))
            ->values();

        $confirmedTournamentIds = Registration::where('user_id', $user->id)
            ->where('status', 'Confirmé')
            ->pluck('tournament_id');

        $myTournaments = \App\Models\Tournament::whereIn('id', $confirmedTournamentIds)
            ->with('game')
            ->get();

        $wins = (int) ($user->competitorProfile->games_won ?? 0);
        $losses = (int) ($user->competitorProfile->games_loss ?? 0);
        $playedMatches = $wins + $losses;

        $teamIds = $user->teams()->pluck('teams.id')->toArray();

        $upcomingChallenges = \App\Models\Matchh::with(['tournament', 'team1', 'team2'])
            ->where(function ($query) use ($teamIds) {
                $query->whereIn('team1_id', $teamIds)
                    ->orWhereIn('team2_id', $teamIds);
            })
            ->where('status', 'Programmé')
            ->orderBy('played_at')
            ->get();

        $stats = [
            'wins' => $wins,
            'losses' => $losses,
            'win_rate' => $playedMatches > 0 ? round(($wins / $playedMatches) * 100) : 0,
            'played_matches' => $playedMatches,
            'active_tournaments' => $myTournaments->count(),
            'challenge_cards' => $upcomingChallenges->count(),
        ];

        return view('competitor.profile', compact(
            'user',
            'stats',
            'pendingInvites',
            'pendingApprovals',
            'myTournaments',
            'upcomingChallenges'
        ));
    }
    
}

<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Matchh;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class TournamentExplorerController extends Controller
{
    public function index(Request $request): View
    {
        $currentFilter = $request->query('filter', 'all');

        $query = Tournament::with(['game', 'category'])
            ->withCount('teams')
            ->withCount([
                'registrations as confirmed_registrations_count' => fn ($registrationQuery) => $registrationQuery
                    ->where('status', 'Confirmé'),
            ]);

        switch ($currentFilter) {
            case 'ouvertes':
                $query->where('status', 'Ouvert')
                    ->where('event_date', '>', now())
                    ->havingRaw('confirmed_registrations_count < max_capacity');
                break;
            case 'a_venir':
                $query->where('status', 'À venir');
                break;
            case 'terminees':
                $query->where('status', 'Terminé');
                break;
        }

        $tournaments = $query->latest()->get();

        return view('competitor.tournaments.index', compact('tournaments', 'currentFilter'));
    }

    public function show(Tournament $tournament): View
    {
        $tournament->load([
            'game',
            'category',
            'organizer',
            'teams.members',
            'matches.team1.members',
            'matches.team2.members',
        ]);

        $user = auth()->user();
        $userTeam = $tournament->teams()
            ->whereHas('members', fn ($query) => $query->where('user_id', $user->id))
            ->with('members')
            ->first();

        $registration = $tournament->registrations()
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $isRegistered = (bool) $registration && in_array($registration->status, ['Confirmé', 'En attente'], true);

        $matches = $tournament->matches
            ->sortBy([
                ['round', 'asc'],
                ['position_in_round', 'asc'],
                ['played_at', 'asc'],
            ])
            ->values();

        $challengeCards = $matches
            ->filter(function (Matchh $match) use ($userTeam) {
                if (!$userTeam) {
                    return false;
                }

                return $match->team1_id === $userTeam->id || $match->team2_id === $userTeam->id;
            })
            ->values();

        $nextMatch = $challengeCards
            ->first(fn (Matchh $match) => in_array($match->status, ['Programmé', 'En attente'], true));

        $bracketRounds = $matches
            ->groupBy(fn (Matchh $match) => $match->round ?: 1)
            ->sortKeys();

        $canJoin = !$isRegistered && $tournament->isOpenForRegistration();

        return view('competitor.tournaments.show', compact(
            'tournament',
            'isRegistered',
            'registration',
            'userTeam',
            'challengeCards',
            'nextMatch',
            'bracketRounds',
            'canJoin'
        ));
    }
}

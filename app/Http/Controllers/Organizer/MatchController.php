<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Matchh; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MatchController extends Controller
{
    public function index(Tournament $tournament)
    {
        abort_unless($this->canManageTournament($tournament), 403);

        $teams = $tournament->teams()
            ->whereHas('registrations', fn ($query) => $query->where('status', 'Confirmé'))
            ->with('members')
            ->get();

        $planningMatches = $tournament->matches()
            ->with(['team1', 'team2', 'winnerTeam'])
            ->latest()
            ->get();

        $bracketMatches = $tournament->matches()
            ->whereNotNull('round')
            ->orderBy('round')
            ->orderBy('position_in_round')
            ->get();

        $bracketRounds = $bracketMatches->groupBy('round')->sortKeys();

        $assignedTeamIds = $bracketMatches->pluck('team1_id')
            ->merge($bracketMatches->pluck('team2_id'))
            ->filter()
            ->unique()
            ->values();

        $availableTeams = $teams->whereNotIn('id', $assignedTeamIds)->values();

        $roundOneMatches = (int) ($bracketRounds->first()?->count() ?? 0);

        return view('organizer.matches.index', compact(
            'tournament',
            'teams',
            'planningMatches',
            'bracketRounds',
            'availableTeams',
            'roundOneMatches'
        ));
    }

    public function store(Request $request, Tournament $tournament)
    {
        abort_unless($this->canManageTournament($tournament), 403);

        $request->validate([
            'team1_id' => 'required|exists:teams,id|different:team2_id',
            'team2_id' => 'required|exists:teams,id',
            'played_at' => 'nullable|date',
        ], [
            'team1_id.different' => 'Les deux équipes doivent être différentes !'
        ]);

        abort_unless(
            $tournament->teams()->whereKey([$request->team1_id, $request->team2_id])->count() === 2,
            422,
            'Les équipes doivent appartenir à ce tournoi.'
        );

        $tournament->matches()->create([
            'team1_id' => $request->team1_id,
            'team2_id' => $request->team2_id,
            'status' => 'Programmé',
            'played_at' => $request->played_at,
        ]);

        return back()->with('success', 'Le match a été programmé avec succès ! ⚔️');
    }

    public function generateBracket(Tournament $tournament)
    {
        abort_unless($this->canManageTournament($tournament), 403);

        $teams = $tournament->teams()
            ->whereHas('registrations', fn ($query) => $query->where('status', 'Confirmé'))
            ->orderBy('id')
            ->get()
            ->values();

        $teamCount = $teams->count();

        if ($teamCount < 2) {
            return back()->with('error', 'Il faut au moins 2 équipes pour générer un arbre.');
        }

        DB::transaction(function () use ($tournament, $teams, $teamCount) {

            $tournament->matches()->delete();

            $bracketSize = 2;
            while ($bracketSize < $teamCount) {
                $bracketSize *= 2;
            }

            $totalRounds = (int) log($bracketSize, 2);
            $createdRounds = [];

            for ($round = 1; $round <= $totalRounds; $round++) {
                $matchCount = (int) ($bracketSize / (2 ** $round));
                $createdRounds[$round] = [];

                for ($position = 1; $position <= $matchCount; $position++) {
                    $createdRounds[$round][$position] = $tournament->matches()->create([
                        'status' => 'En attente',
                        'round' => $round,
                        'position_in_round' => $position,
                    ]);
                }
            }

            for ($round = 1; $round < $totalRounds; $round++) {
                foreach ($createdRounds[$round] as $position => $match) {
                    $match->update([
                        'next_match_id' => $createdRounds[$round + 1][(int) ceil($position / 2)]->id,
                    ]);
                }
            }

            $teamIds = $teams->pluck('id')->pad($bracketSize, null)->values();
            $firstRoundMatches = collect($createdRounds[1])->values();

            foreach ($firstRoundMatches as $index => $match) {

                $team1Id = $teamIds[$index * 2] ?? null;
                $team2Id = $teamIds[$index * 2 + 1] ?? null;

                $payload = [
                    'team1_id' => $team1Id,
                    'team2_id' => $team2Id,
                    'winner_team_id' => null,
                    'score' => null,
                    'status' => 'En attente',
                ];

                $payload['status'] = $team1Id && $team2Id
                    ? 'Programmé'
                    : 'En attente';

                $match->update($payload);

                if (($team1Id && !$team2Id) || (!$team1Id && $team2Id)) {
                    $match->update([
                        'winner_team_id' => $team1Id ?: $team2Id,
                        'status' => 'Terminé',
                    ]);

                    $this->advanceWinner($match->fresh());
                }
            }
        });

        return back()->with('success', 'Arbre généré. Tu peux maintenant déplacer les équipes et gérer la progression.');
    }

    public function updateScore(Request $request, Tournament $tournament, $matchId)
    {
        abort_unless($this->canManageTournament($tournament), 403);

        $match = Matchh::where('tournament_id', $tournament->id)
            ->findOrFail($matchId);

        $request->validate([
            'score_team1' => 'required|integer|min:0',
            'score_team2' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($match, $request) {

            $previousWinnerId = $match->winner_team_id;

            $winner_id = null;
            $status = 'Terminé';

            if ($request->score_team1 == $request->score_team2) {
                $status = 'Draw';
            } elseif ($request->score_team1 > $request->score_team2) {
                $winner_id = $match->team1_id;
            } else {
                $winner_id = $match->team2_id;
            }

            $match->update([
                'score' => $request->score_team1 . ' - ' . $request->score_team2,
                'winner_team_id' => $winner_id,
                'status' => $status
            ]);

            $this->syncProfilesForResult(
                $match->fresh(['team1.members', 'team2.members']),
                $previousWinnerId,
                $winner_id
            );

            $this->advanceWinner($match);
        });

        return back()->with('success', 'Le score a été enregistré et le match est terminé !');
    }

    public function updateBracket(Request $request, Tournament $tournament)
    {
        abort_unless($this->canManageTournament($tournament), 403);

        $validated = $request->validate([
            'match_id' => ['required', Rule::exists('matches', 'id')->where(fn ($q) => $q->where('tournament_id', $tournament->id))],
            'slot' => 'required|in:team1_id,team2_id',
            'team_id' => [
                'nullable',
                Rule::exists('teams', 'id')->where(fn ($q) => $q->where('tournament_id', $tournament->id)),
            ],
        ]);

        $match = Matchh::where('tournament_id', $tournament->id)
            ->findOrFail($validated['match_id']);

        $teamId = $validated['team_id'] ?? null;

        DB::transaction(function () use ($tournament, $match, $validated, $teamId) {

            if ($teamId) {
                $this->detachTeamFromOtherMatches($tournament->id, $teamId, [$match->id]);
            }

            $match->refresh();

            $otherSlot = $validated['slot'] === 'team1_id' ? 'team2_id' : 'team1_id';

            if ($teamId && $match->{$otherSlot} === $teamId) {
                $match->{$otherSlot} = null;
            }

            $match->{$validated['slot']} = $teamId;
            $match->save();

            $this->normalizeMatchTree($match);
        });

        return response()->json(['success' => true, 'message' => 'Bracket mis à jour.']);
    }

    public function setWinner(Request $request, Tournament $tournament)
    {
        abort_unless($this->canManageTournament($tournament), 403);

        $validated = $request->validate([
            'match_id' => ['required', Rule::exists('matches', 'id')->where(fn ($q) => $q->where('tournament_id', $tournament->id))],
            'winner_team_id' => ['required', Rule::exists('teams', 'id')->where(fn ($q) => $q->where('tournament_id', $tournament->id))],
        ]);

        $match = Matchh::where('id', $validated['match_id'])
            ->where('tournament_id', $tournament->id)
            ->firstOrFail();

        abort_unless(
            in_array($validated['winner_team_id'], array_filter([$match->team1_id, $match->team2_id]), true),
            422
        );

        DB::transaction(function () use ($match, $validated) {

            $previousWinnerId = $match->winner_team_id;

            $match->update([
                'winner_team_id' => $validated['winner_team_id'],
                'status' => 'Terminé',
            ]);

            $this->syncProfilesForResult(
                $match->fresh(['team1.members', 'team2.members']),
                $previousWinnerId,
                (int) $validated['winner_team_id']
            );

            $this->advanceWinner($match);
        });

        return response()->json(['success' => true]);
    }

    private function normalizeMatchTree(Matchh $match): void
    {
        $match->refresh();

        $validTeamIds = collect([$match->team1_id, $match->team2_id])->filter()->values();
        $previousWinner = $match->winner_team_id;

        if ($match->winner_team_id && !$validTeamIds->contains($match->winner_team_id)) {
            $match->winner_team_id = null;
            $match->score = null;
        }

        if ($match->winner_team_id) {
            $match->status = 'Terminé';
        } elseif ($validTeamIds->count() === 2) {
            $match->status = 'Programmé';
        } else {
            $match->status = 'En attente';
        }

        $match->save();

        if ($previousWinner && $previousWinner !== $match->winner_team_id) {
            $this->clearProgression($match, $previousWinner);
        }
    }

    private function clearProgression(Matchh $match, int $winnerTeamId): void
    {
        if (!$match->next_match_id) return;

        $nextMatch = Matchh::find($match->next_match_id);
        if (!$nextMatch) return;

        $targetSlot = $this->nextSlotFor($match);

        if ($nextMatch->{$targetSlot} === $winnerTeamId) {
            $nextMatch->{$targetSlot} = null;
            $nextMatch->save();
            $this->normalizeMatchTree($nextMatch);
        }
    }

    private function advanceWinner(Matchh $match): void
    {
        if (
            !$match->next_match_id ||
            !$match->winner_team_id ||
            $match->status === 'Draw'
        ) {
            return;
        }

        $this->detachTeamFromOtherMatches(
            $match->tournament_id,
            $match->winner_team_id,
            [$match->id]
        );

        $nextMatch = Matchh::find($match->next_match_id);
        if (!$nextMatch) return;

        $targetSlot = $this->nextSlotFor($match);
        $otherSlot = $targetSlot === 'team1_id' ? 'team2_id' : 'team1_id';

        if ($nextMatch->{$otherSlot} === $match->winner_team_id) {
            $nextMatch->{$otherSlot} = null;
        }

        $nextMatch->{$targetSlot} = $match->winner_team_id;
        $nextMatch->save();

        $this->normalizeMatchTree($nextMatch);
    }

    private function syncProfilesForResult(Matchh $match, ?int $previousWinnerId, ?int $newWinnerId): void
    {
        if (!$newWinnerId || $match->status === 'Draw' || $previousWinnerId === $newWinnerId) {
            return;
        }

        if ($previousWinnerId) {
            $this->applyProfileDelta($match, $previousWinnerId, -1);
        }

        $this->applyProfileDelta($match, $newWinnerId, 1);
    }

    private function applyProfileDelta(Matchh $match, int $winnerTeamId, int $direction): void
    {
        $winningTeam = $winnerTeamId === $match->team1_id ? $match->team1 : $match->team2;
        $losingTeam = $winnerTeamId === $match->team1_id ? $match->team2 : $match->team1;

        if (!$winningTeam || !$losingTeam) return;

        foreach ($winningTeam->members as $member) {
            $profile = $member->competitorProfile()->firstOrCreate(['user_id' => $member->id]);
            $profile->increment('games_won', $direction);
        }

        foreach ($losingTeam->members as $member) {
            $profile = $member->competitorProfile()->firstOrCreate(['user_id' => $member->id]);
            $profile->increment('games_loss', $direction);
        }
    }

    private function detachTeamFromOtherMatches(int $tournamentId, int $teamId, array $exceptMatchIds = []): void
    {
        Matchh::where('tournament_id', $tournamentId)
            ->whereNotIn('id', $exceptMatchIds)
            ->where(function ($query) use ($teamId) {
                $query->where('team1_id', $teamId)
                      ->orWhere('team2_id', $teamId);
            })
            ->each(function (Matchh $existingMatch) use ($teamId) {

                if ($existingMatch->team1_id === $teamId) {
                    $existingMatch->team1_id = null;
                }

                if ($existingMatch->team2_id === $teamId) {
                    $existingMatch->team2_id = null;
                }

                $existingMatch->save();
                $this->normalizeMatchTree($existingMatch);
            });
    }

    private function nextSlotFor(Matchh $match): string
    {
        return ($match->position_in_round % 2 === 1) ? 'team1_id' : 'team2_id';
    }

    private function canManageTournament(Tournament $tournament): bool
    {
        return auth()->user()->hasRole('Admin')
            || $tournament->organizer_id === auth()->id();
    }
}
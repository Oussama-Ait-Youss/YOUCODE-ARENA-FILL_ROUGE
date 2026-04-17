<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller

{
    public function index()
    {
        $user = auth()->user();

        $registrations = Registration::with(['tournament.game'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $stats = [
            'total_tournaments' => $registrations->whereIn('status', ['Confirmé', 'Accepté'])->count(),
            'pending_invitations' => $registrations->where('status', 'En attente')->count(),
        ];

        return view('competitor.teams.index', compact('registrations', 'stats', 'user'));
    }

    public function create(Tournament $tournament)
    {
        $tournament->load('game');

        abort_unless($tournament->isOpenForRegistration(), 403, 'Les inscriptions sont fermées pour ce tournoi.');

        $isDuo = $tournament->game?->requiresTeamInvite() ?? false;

        return view('competitor.teams.create', compact('tournament', 'isDuo'));
    }



    public function store(Request $request, Tournament $tournament)
    {
        $tournament->load('game');

        if (!$tournament->isOpenForRegistration()) {
            return redirect()
                ->route('competitor.tournaments.show', $tournament)
                ->with('error', 'Les inscriptions sont fermées pour ce tournoi.');
        }

        $alreadyRegistered = Registration::where('user_id', Auth::id())
                                         ->where('tournament_id', $tournament->id)
                                         ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('competitor.tournaments.index')
                             ->with('error', 'Action refusée : Vous participez déjà à ce tournoi !');
        }

        $isDuo = $tournament->game?->requiresTeamInvite() ?? false;

        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'partner_email' => $isDuo ? 'nullable|email|exists:users,email' : 'nullable',
        ], [
            'name.unique' => 'Ce nom d\'équipe est déjà pris par d\'autres joueurs.',
            'partner_email.exists' => 'Aucun joueur trouvé avec cet email sur YouCode Arena.'
        ]);

        $requestedSeats = 1 + (filled($request->partner_email) ? 1 : 0);

        if (($tournament->registered_count + $requestedSeats) > $tournament->max_capacity) {
            return redirect()
                ->route('competitor.tournaments.show', $tournament)
                ->with('error', 'Le quota du tournoi est atteint pour cette inscription.');
        }

        $message = 'Inscription validée. Bonne chance dans l\'arène.';

        DB::transaction(function () use ($request, $tournament, &$message) {
            $team = Team::create([
                'tournament_id' => $tournament->id,
                'name' => $request->name,
            ]);

            $team->members()->attach(Auth::id(), ['joined_at' => now()]);
            // $team->members()->attach(Auth::id());

            Registration::create([
                'user_id' => Auth::id(),
                'tournament_id' => $tournament->id,
                'status' => 'Confirmé',
                'registration_date' => now(),
            ]);

            if ($request->filled('partner_email')) {
                $partner = User::where('email', $request->partner_email)->first();

                if ($partner && $partner->id === Auth::id()) {
                    $message = 'Inscription solo enregistrée. Tu ne peux pas t\'inviter toi-même.';
                    return;
                }

                $partnerAlreadyRegistered = $partner
                    ? Registration::where('user_id', $partner->id)
                        ->where('tournament_id', $tournament->id)
                        ->exists()
                    : false;

                if ($partner && !$partnerAlreadyRegistered) {
                    Registration::create([
                        'user_id' => $partner->id,
                        'tournament_id' => $tournament->id,
                        'status' => 'En attente',
                        'registration_date' => now(),
                    ]);

                    $message = 'Équipe créée. Invitation envoyée à ' . $partner->username . '.';
                } elseif ($partner) {
                    $message = 'Ton inscription est confirmée, mais ce coéquipier participe déjà à ce tournoi.';
                }
            }
        });

        return redirect()->route('competitor.tournaments.show', $tournament)->with('success', $message);
    }

    public function leave(Tournament $tournament)
    {
        $user = auth()->user();

        $registration = Registration::where('user_id', $user->id)
            ->where('tournament_id', $tournament->id)
            ->first();

        if (!$registration) {
            return redirect()->route('competitor.tournaments.show', $tournament)
                ->with('error', 'Aucune inscription trouvée pour ce tournoi.');
        }

        DB::transaction(function () use ($user, $tournament, $registration) {
            $team = $user->teams()
                ->where('tournament_id', $tournament->id)
                ->first();

            if ($team) {
                $team->members()->detach($user->id);

                if ($team->members()->count() === 0) {
                    $team->delete();
                }
            }

            $registration->delete();
        });

        return redirect()->route('competitor.tournaments.index')
            ->with('success', 'Tu as quitté le tournoi et ta place a été libérée.');
    }
}

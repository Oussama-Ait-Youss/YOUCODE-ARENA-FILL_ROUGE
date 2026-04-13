<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

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
        'total_tournaments' => $registrations->where('status', 'Confirmé')->count(),
        'pending_invitations' => $registrations->where('status', 'En attente')->count(),
    ];

    return view('competitor.teams.index', compact('registrations', 'stats', 'user'));
}
   public function create(Tournament $tournament)
{
    $tournament->load('game');
    
    
    $isDuo = str_contains(strtolower($tournament->game->name), 'babyfoot');

    return view('competitor.teams.create', compact('tournament', 'isDuo'));
}



    public function store(Request $request, Tournament $tournament)
    {
        $alreadyRegistered = Registration::where('user_id', Auth::id())
                                         ->where('tournament_id', $tournament->id)
                                         ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('competitor.tournaments.index')
                             ->with('error', 'Action refusée : Vous participez déjà à ce tournoi !');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'partner_email' => 'nullable|email|exists:users,email',
        ], [
            'name.unique' => 'Ce nom d\'équipe est déjà pris par d\'autres joueurs.',
            'partner_email.exists' => 'Aucun joueur trouvé avec cet email sur YouCode Arena.'
        ]);

        $team = Team::create([
            'tournament_id' => $tournament->id,
            'name' => $request->name,
        ]);

        Registration::create([
            'user_id' => Auth::id(),
            'tournament_id' => $tournament->id,
            'status' => 'Confirmé',
            'registration_date' => now(),
        ]);

        if ($request->filled('partner_email')) {
            $partner = User::where('email', $request->partner_email)->first();
            
            if ($partner->id === Auth::id()) {
                 return redirect()->route('competitor.teams.index')
                                  ->with('success', 'Équipe créée en Solo (Vous ne pouvez pas vous inviter vous-même !)');
            }

            $partnerAlreadyRegistered = Registration::where('user_id', $partner->id)
                                                    ->where('tournament_id', $tournament->id)
                                                    ->exists();

            if (!$partnerAlreadyRegistered) {
                Registration::create([
                    'user_id' => $partner->id,
                    'tournament_id' => $tournament->id,
                    'status' => 'En attente', 
                    'registration_date' => now(),
                ]);
                $message = 'Équipe créée ! Une invitation a été envoyée à ' . $partner->username . '.';
            } else {
                $message = 'Équipe créée ! Mais attention, ' . $partner->username . ' participe déjà à ce tournoi avec une autre équipe.';
            }
        } else {
            $message = 'Félicitations ! Votre équipe a été créée avec succès.';
        }

        return redirect()->route('competitor.teams.index')->with('success', $message);
    }
}

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
    
        $registrations = Registration::with(['tournament.teams', 'tournament.game'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('competitor.teams.index', compact('registrations'));
    }
   public function create(Tournament $tournament)
{
    $tournament->load('game');
    
    
    $isDuo = str_contains(strtolower($tournament->game->name), 'babyfoot');

    return view('competitor.teams.create', compact('tournament', 'isDuo'));
}
    public function store(Request $request, Tournament $tournament)
{
    // 1. Validation renforcée
    $request->validate([
        'name' => 'required|string|max:255|unique:teams,name',
        'partner_email' => 'nullable|email|exists:users,email', // On vérifie si le partenaire existe déjà sur YouCode Arena
    ]);

    // 2. Création de l'équipe
    $team = Team::create([
        'tournament_id' => $tournament->id,
        'name' => $request->name,
    ]);

    // 3. Inscription du Créateur (Toi)
    Registration::create([
        'user_id' => Auth::id(),
        'tournament_id' => $tournament->id,
        'status' => 'Confirmé', // Le créateur est validé d'office
        'registration_date' => now(),
    ]);

    // 4. Logique d'Invitation (Tâche 3.2)
    if ($request->filled('partner_email')) {
        $partner = User::where('email', $request->partner_email)->first();
        
        // On crée une inscription "En attente" pour le partenaire
        Registration::create([
            'user_id' => $partner->id,
            'tournament_id' => $tournament->id,
            'status' => 'En attente', // Il devra accepter l'invitation
            'registration_date' => now(),
        ]);
        
  
    }

    return redirect()->route('competitor.teams.index')
                     ->with('success', 'Équipe créée ! ' . ($request->partner_email ? 'Invitation envoyée à votre coéquipier.' : ''));
}
}

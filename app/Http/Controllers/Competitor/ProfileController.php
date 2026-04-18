<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Matchh;
use App\Models\Tournament;
use Illuminate\Contracts\View\View;

class ProfileController extends Controller
{
    public function show()
{
    $user = auth()->user();

    // 1. On récupère les invitations en attente (pour la section du haut)
    $pendingInvites = $user->registrations()
        ->where('status', 'En attente')
        ->with('tournament')
        ->get();

    // 2. 🚨 LE FIX EST ICI : On récupère tous les tournois confirmés/acceptés
    // On utilise pluck() pour récupérer les IDs sans créer d'erreur de relation
    $confirmedTournamentIds = \App\Models\Registration::where('user_id', $user->id)
        ->whereIn('status', ['Confirmé', 'Accepté']) // On check les deux mots au cas où !
        ->pluck('tournament_id');

    $myTournaments = \App\Models\Tournament::whereIn('id', $confirmedTournamentIds)
        ->with('game') // Pour afficher le nom du jeu (ex: Valorant)
        ->get();

    // 3. Tes statistiques (Garde ta logique actuelle ici)
    $stats = [
        'wins' => $user->wins ?? 0,
        'losses' => $user->losses ?? 0,
        'win_rate' => ($user->played_matches > 0) ? round(($user->wins / $user->played_matches) * 100) : 0,
        'played_matches' => $user->played_matches ?? 0,
        'active_tournaments' => $myTournaments->count(),
        'challenge_cards' => 0,
    ];

    $upcomingChallenges = []; // Si tu as une logique pour les matchs, mets-la ici

    return view('competitor.profile', compact(
        'user', 
        'stats', 
        'pendingInvites', 
        'myTournaments', 
        'upcomingChallenges'
    ));
}
    
}

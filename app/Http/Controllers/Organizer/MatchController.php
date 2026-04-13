<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Matchh; 
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Tournament $tournament)
    {
        $teams = $tournament->teams;

        $matches = $tournament->matches()->with(['team1', 'team2'])->latest()->get();

        return view('organizer.matches.index', compact('tournament', 'teams', 'matches'));
    }

    public function store(Request $request, Tournament $tournament)
    {
        $request->validate([
            'team1_id' => 'required|exists:teams,id|different:team2_id',
            'team2_id' => 'required|exists:teams,id',
            'played_at' => 'nullable|date', 
        ], [
            'team1_id.different' => 'Les deux équipes doivent être différentes !'
        ]);

        $tournament->matches()->create([
            'team1_id' => $request->team1_id,
            'team2_id' => $request->team2_id,
            'status' => 'Programmé',
            'played_at' => $request->played_at,
        ]);

        return back()->with('success', 'Le match a été programmé avec succès ! ⚔️');
    }

    public function updateScore(Request $request, Tournament $tournament, $matchId)
    {
      
        $match = \App\Models\Matchh::findOrFail($matchId); 

        $request->validate([
            'score_team1' => 'required|integer|min:0',
            'score_team2' => 'required|integer|min:0',
        ]);

        $winner_id = null;
        if ($request->score_team1 > $request->score_team2) {
            $winner_id = $match->team1_id;
        } elseif ($request->score_team2 > $request->score_team1) {
            $winner_id = $match->team2_id;
        }

        $match->update([
            'score' => $request->score_team1 . ' - ' . $request->score_team2,
            'winner_team_id' => $winner_id,
            'status' => 'Terminé'
        ]);

        return back()->with('success', 'Le score a été enregistré et le match est terminé ! 🏆');
    }
}
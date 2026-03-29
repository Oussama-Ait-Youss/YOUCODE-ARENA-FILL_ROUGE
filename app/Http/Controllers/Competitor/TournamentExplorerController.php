<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentExplorerController extends Controller
{
    public function index(Request $request)
    {
        $currentFilter = $request->query('filter', 'all');

        $query = Tournament::with(['game', 'category'])
                           ->withCount('teams');

        switch ($currentFilter) {
            case 'ouvertes':
                
                $query->where('status', 'À venir')
                      ->havingRaw('teams_count < max_capacity');
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
}
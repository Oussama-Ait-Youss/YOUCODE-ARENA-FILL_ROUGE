<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentExplorerController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::with(['game', 'category', 'organizer'])
            ->whereIn('status', ['À venir', 'Ouvertes'])
            ->where('event_date', '>', now())
            ->orderBy('event_date', 'asc')
            ->get();

        return view('competitor.tournaments.index', compact('tournaments'));
    }
}
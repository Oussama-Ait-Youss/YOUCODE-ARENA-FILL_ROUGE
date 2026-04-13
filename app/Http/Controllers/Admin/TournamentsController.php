<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentsController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::with('organizer')->latest()->get();
        return view('admin.tournaments.index', compact('tournaments'));
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        return redirect()->back()->with('success', 'Le tournoi a été supprimé définitivement (God Mode).');
    }
}
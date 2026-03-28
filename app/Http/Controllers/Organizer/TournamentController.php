<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function index(){
        $tournaments = Tournament::where('organizer_id',Auth::id())->latest()-get();
        return view('organizer.tournaments.index',compact('tournaments'));
    }

    public function create(){
        $games = Game::all();
        $categories = Category::all();
        return view('organizer.tournaments.create',compact('games','categories'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'title'=> 'required|string|max:255',
            'game_id'=> 'required|exists:games,id',
            'category_id'=> 'required|exists:categories,id',
            'max_capacity'=> 'required|integer|min:2',
            'event_date'=> 'required|date|after:today',
        ]);

        Tournament::create([
            'title' => $validated['title'],
            'game_id' => $validated['game_id'],
            'category_id' => $validated['category_id'],
            'max_capacity' => $validated['max_capacity'],
            'event_date' => $validated['event_date'],
            'organizer_id' => Auth::id(),
            'status' => 'À venir', 
        ]);

        return redirect()->route('organizer.tournaments.index')->with('success','Le tournoi a été créé avec succès et est prêt pour les inscri
        ptions !');
    }


}

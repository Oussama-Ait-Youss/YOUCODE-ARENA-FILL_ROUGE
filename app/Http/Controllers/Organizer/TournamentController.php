<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Game;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TournamentController extends Controller
{
    public function index(){
        $tournaments = Tournament::with(['game'])
            ->withCount([
                'registrations as confirmed_registrations_count' => fn ($query) => $query->whereIn('status', ['Confirmé', 'Accepté']),
                'registrations as pending_registrations_count' => fn ($query) => $query->where('status', 'En attente'),
                'matches as scheduled_matches_count',
            ])
            ->where('organizer_id',Auth::id())
            ->latest()
            ->get();

        return view('organizer.tournaments.index',compact('tournaments'));
    }

    public function create(){
        if (!auth()->user()->hasRole('Admin') && \App\Models\Tournament::where('organizer_id', auth()->id())->where('status', 'Ouvert')->exists()) {
            return redirect()->route('organizer.dashboard')
                             ->with('error', 'Tu ne peux avoir qu\'un seul tournoi ouvert aux inscriptions à la fois.');
        }
        $games = Game::all();
        $categories = Category::all();
        $organizers = auth()->user()->hasRole('Admin')
            ? User::whereHas('roles', fn ($query) => $query->where('name', 'Organisateur'))->orderBy('username')->get()
            : collect();

        return view('organizer.tournaments.create',compact('games','categories', 'organizers'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'title'=> 'required|string|max:255',
            'game_id'=> 'required|exists:games,id',
            'category_id'=> 'required|exists:categories,id',
            'max_capacity'=> 'required|integer|min:2',
            'event_date'=> 'required|date|after:today',
            'organizer_id' => [
                Rule::requiredIf(auth()->user()->hasRole('Admin')),
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereExists(function ($subQuery) {
                        $subQuery->selectRaw('1')
                            ->from('user_roles')
                            ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                            ->whereColumn('user_roles.user_id', 'users.id')
                            ->where('roles.name', 'Organisateur');
                    });
                }),
            ],
        ], [
            'organizer_id.required' => 'Sélectionne un organisateur pour ce tournoi.',
            'organizer_id.exists' => 'L’utilisateur sélectionné doit avoir le rôle organisateur.',
        ]);

        $organizerId = auth()->user()->hasRole('Admin')
            ? (int) $validated['organizer_id']
            : Auth::id();

        $this->ensureSingleOpenTournament($organizerId, 'Ouvert');

        Tournament::create([
            'title' => $validated['title'],
            'game_id' => $validated['game_id'],
            'category_id' => $validated['category_id'],
            'max_capacity' => $validated['max_capacity'],
            'event_date' => $validated['event_date'],
            'organizer_id' => $organizerId,
            'status' => 'Ouvert', 
        ]);

        return redirect()->route('organizer.dashboard')
                         ->with('success', 'Tournoi créé avec succès ! L\'arène est prête.');
    }


    public function destroy(Tournament $tournament)
    {
        if (!$this->canManageTournament($tournament)) {
            abort(403, 'Action non autorisée.');
        }

        $tournament->delete();

        return redirect()->route('organizer.tournaments.index')
                         ->with('success', 'Le tournoi a été supprimé avec succès.');
    }


    
    public function edit(Tournament $tournament)
    {
        if (!$this->canManageTournament($tournament)) {
            abort(403, 'Action non autorisée. Ce tournoi ne vous appartient pas.');
        }
       
        $games = Game::all();
        $categories = Category::all();
        $organizers = auth()->user()->hasRole('Admin')
            ? User::whereHas('roles', fn ($query) => $query->where('name', 'Organisateur'))->orderBy('username')->get()
            : collect();

        return view('organizer.tournaments.edit', compact('tournament', 'games', 'categories', 'organizers'));
    }

    
    public function update(Request $request, Tournament $tournament)
    {
        if (!$this->canManageTournament($tournament)) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'game_id' => 'required|exists:games,id',
            'category_id' => 'required|exists:categories,id',
            'max_capacity' => 'required|integer|min:2',
            'event_date' => 'required|date',
            'organizer_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereExists(function ($subQuery) {
                        $subQuery->selectRaw('1')
                            ->from('user_roles')
                            ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                            ->whereColumn('user_roles.user_id', 'users.id')
                            ->where('roles.name', 'Organisateur');
                    });
                }),
            ],
            'status' => 'nullable|in:À venir,Ouvert,Fermé,Terminé',
        ]);

        $organizerId = $tournament->organizer_id;
        if (auth()->user()->hasRole('Admin') && !empty($validated['organizer_id'])) {
            $organizerId = (int) $validated['organizer_id'];
        }

        $status = $validated['status'] ?? $tournament->status;
        $this->ensureSingleOpenTournament($organizerId, $status, $tournament->id);

        $validated['organizer_id'] = $organizerId;

        $tournament->update($validated);

        return redirect()->route('organizer.tournaments.index')
                         ->with('success', 'Le tournoi a été modifié avec succès !');
    }
    public function updateStatus(Request $request, Tournament $tournament)
    {
        if (!$this->canManageTournament($tournament)) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'status' => 'required|in:À venir,Ouvert,Fermé,Terminé'
        ]);

        if (
            $validated['status'] === 'Ouvert' &&
            Tournament::where('organizer_id', $tournament->organizer_id)
                ->where('id', '!=', $tournament->id)
                ->where('status', 'Ouvert')
                ->exists()
        ) {
            return redirect()->back()->with('error', 'Cet organisateur a déjà un tournoi ouvert.');
        }

        $tournament->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Statut du tournoi mis à jour.');
    }

    public function data(Tournament $tournament)
    {
        if (!$this->canManageTournament($tournament)) {
            return response()->json(['success' => false], 403);
        }

        // Fetch accepted teams via registrations or teams model
        $teams = $tournament->teams()->with('members')->get();
        $matches = $tournament->matches()->get();

        return response()->json([
            'success' => true,
            'teams' => $teams,
            'matches' => $matches
        ]);
    }

    private function canManageTournament(Tournament $tournament): bool
    {
        return auth()->user()->hasRole('Admin') || $tournament->organizer_id === Auth::id();
    }

    private function ensureSingleOpenTournament(int $organizerId, string $status, ?int $ignoreTournamentId = null): void
    {
        if ($status !== 'Ouvert') {
            return;
        }

        $query = Tournament::where('organizer_id', $organizerId)
            ->where('status', 'Ouvert');

        if ($ignoreTournamentId) {
            $query->where('id', '!=', $ignoreTournamentId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'organizer_id' => 'Cet organisateur gère déjà un tournoi ouvert.',
            ]);
        }
    }

}

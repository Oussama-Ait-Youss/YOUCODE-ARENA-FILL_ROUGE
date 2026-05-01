<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Game;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TournamentsController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $search = trim($request->string('q')->toString());
        $perPage = max(10, min(50, $request->integer('per_page', 10)));

        $tournaments = Tournament::query()
            ->with(['organizer', 'game'])
            ->withCount([
                'registrations as confirmed_registrations_count' => fn ($query) => $query->where('status', 'Confirmé'),
                'registrations as pending_registrations_count' => fn ($query) => $query->where('status', 'En attente'),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('title', 'like', "%{$search}%")
                        ->orWhereHas('game', fn ($gameQuery) => $gameQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('organizer', fn ($organizerQuery) => $organizerQuery->where('username', 'like', "%{$search}%"));
                });
            })
            ->when(in_array($status, ['Ouvert', 'Fermé', 'À venir', 'Terminé'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $tournamentStats = [
            'total' => Tournament::count(),
            'open' => Tournament::where('status', 'Ouvert')->count(),
            'upcoming' => Tournament::where('status', 'À venir')->count(),
            'completed' => Tournament::where('status', 'Terminé')->count(),
        ];

        return view('admin.tournaments.index', compact('tournaments', 'tournamentStats', 'status', 'search', 'perPage'));
    }

    public function create()
    {
        $games = Game::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $organizers = $this->organizerOptions();
        $statusOptions = ['À venir', 'Ouvert', 'Fermé', 'Terminé'];

        return view('admin.tournaments.create', compact('games', 'categories', 'organizers', 'statusOptions'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTournament($request);
        $this->ensureOrganizerCanOpenOnlyOneTournament(
            $validated['organizer_id'],
            $validated['status']
        );

        Tournament::create($validated);

        return redirect()
            ->route('admin.tournaments.index')
            ->with('success', 'Le tournoi a été créé et assigné avec succès.');
    }

    public function edit(Tournament $tournament)
    {
        $games = Game::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $organizers = $this->organizerOptions();
        $statusOptions = ['À venir', 'Ouvert', 'Fermé', 'Terminé'];

        return view('admin.tournaments.edit', compact('tournament', 'games', 'categories', 'organizers', 'statusOptions'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $validated = $this->validateTournament($request);
        $this->ensureOrganizerCanOpenOnlyOneTournament(
            $validated['organizer_id'],
            $validated['status'],
            $tournament->id
        );

        $tournament->update($validated);

        return redirect()
            ->route('admin.tournaments.index')
            ->with('success', 'Le tournoi a été mis à jour avec succès.');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();

        return back()->with('success', 'Le tournoi a été supprimé définitivement (God Mode).');
    }

    private function validateTournament(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'game_id' => ['required', Rule::exists('games', 'id')],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'organizer_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereExists(function ($subQuery) {
                        $subQuery->selectRaw('1')
                            ->from('user_roles')
                            ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                            ->whereColumn('user_roles.user_id', 'users.id')
                            ->whereIn('roles.name', ['Organisateur', 'Admin']);
                    });
                }),
            ],
            'status' => ['required', Rule::in(['À venir', 'Ouvert', 'Fermé', 'Terminé'])],
            'max_capacity' => ['required', 'integer', 'min:2'],
            'event_date' => ['required', 'date'],
        ], [
            'organizer_id.required' => 'Un organisateur doit être assigné avant la création du tournoi.',
            'organizer_id.exists' => 'L’utilisateur sélectionné doit avoir un rôle organisateur ou administrateur.',
        ]);
    }

    private function organizerOptions()
    {
        return User::query()
            ->with('roles')
            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['Organisateur', 'Admin']))
            ->orderBy('username')
            ->get();
    }

    private function ensureOrganizerCanOpenOnlyOneTournament(int $organizerId, string $status, ?int $ignoreTournamentId = null): void
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
                'organizer_id' => 'Cet organisateur gère déjà un tournoi ouvert aux inscriptions.',
            ]);
        }
    }
}

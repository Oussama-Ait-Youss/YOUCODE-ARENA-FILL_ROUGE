<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matchh;
use App\Models\Registration;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roleOptions = ['Admin', 'Organisateur', 'Jury', 'Compétiteur'];
        $status = $request->string('status')->toString();
        $role = $request->string('role')->toString();
        $search = trim($request->string('q')->toString());
        $perPage = max(10, min(50, $request->integer('per_page', 10)));

        $users = User::query()
            ->with('roles')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");

                    if (is_numeric($search)) {
                        $innerQuery->orWhere('id', (int) $search);
                    }
                });
            })
            ->when(in_array($status, ['active', 'banned'], true), function ($query) use ($status) {
                $query->where('is_banned', $status === 'banned');
            })
            ->when(in_array($role, $roleOptions, true), function ($query) use ($role) {
                $query->whereHas('roles', fn ($roleQuery) => $roleQuery->where('name', $role));
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'roleOptions', 'search', 'role', 'status', 'perPage'));
    }

    public function show(User $user)
    {
        $user->load(['roles', 'competitorProfile', 'teams.tournament', 'posts', 'comments']);

        $teamIds = $user->teams->pluck('id');
        $userStats = [
            'teams_count' => $user->teams->count(),
            'registrations_count' => Registration::where('user_id', $user->id)->count(),
            'organized_tournaments_count' => Tournament::where('organizer_id', $user->id)->count(),
            'won_matches_count' => $teamIds->isEmpty() ? 0 : Matchh::whereIn('winner_team_id', $teamIds)->count(),
            'posts_count' => $user->posts->count(),
            'comments_count' => $user->comments->count(),
        ];

        return view('admin.users.show', compact('user', 'userStats'));
    }

    public function changeRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in(['Compétiteur', 'Organisateur', 'Jury'])],
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tu ne peux pas modifier ton propre rôle.');
        }

        if ($user->hasRole('Admin')) {
            return back()->with('error', 'Les comptes administrateurs sont protégés contre ce changement.');
        }

        if ($user->primaryRoleName() === $validated['role']) {
            return back()->with('success', 'Le rôle demandé est déjà actif.');
        }

        $user->assignRole($validated['role'], auth()->id());

        return back()->with('success', "Le rôle de {$user->username} a été mis à jour avec succès !");
    }

    public function toggleBan(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tu ne peux pas te bannir toi-même.');
        }

        if ($user->hasRole('Admin')) {
            return back()->with('error', 'Les comptes administrateurs ne peuvent pas être bannis depuis ce module.');
        }

        $user->update([
            'is_banned' => !$user->is_banned,
            'banned_by' => $user->is_banned ? null : auth()->user()->username,
            'banned_reason' => $user->is_banned ? null : 'Banni par un administrateur',
        ]);

        $message = $user->fresh()->is_banned
            ? "L'utilisateur {$user->username} a été banni."
            : "L'utilisateur {$user->username} a été réintégré.";

        return back()->with('success', $message);
    }
}

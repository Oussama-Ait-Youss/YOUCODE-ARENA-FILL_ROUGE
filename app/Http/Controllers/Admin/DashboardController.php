<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Registration;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Matchh; 
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalBannedUsers = User::where('is_banned', true)->count();
        $activeUsers = $totalUsers - $totalBannedUsers;
        $roleCounts = Role::withCount('users')->get()->keyBy('name');
        $totalCompetitors = $roleCounts->get('Compétiteur')->users_count ?? 0;
        $totalOrganizers = $roleCounts->get('Organisateur')->users_count ?? 0;
        $totalAdmins = $roleCounts->get('Admin')->users_count ?? 0;
        $totalJuries = $roleCounts->get('Jury')->users_count ?? 0;
        $totalTournaments = Tournament::count();
        $activeTournaments = Tournament::where('status', 'Ouvert')->count();
        $completedTournaments = Tournament::where('status', 'Terminé')->count();
        $totalPosts = Post::count();
        $totalComments = Comment::count();
        $totalMatches = Matchh::count();
        $totalTeams = Team::count();
        $totalRegistrations = Registration::count();
        $recentUsers = User::with('roles')->latest()->take(6)->get();
        $recentTournaments = Tournament::with(['organizer', 'game'])->latest()->take(6)->get();
        $recentActivities = $this->buildRecentActivities();

        $userStatusBreakdown = collect([
            [
                'label' => 'Actifs',
                'count' => $activeUsers,
                'color' => 'success',
            ],
            [
                'label' => 'Bannis',
                'count' => $totalBannedUsers,
                'color' => 'crimson',
            ],
        ]);

        $roleBreakdown = collect([
            [
                'label' => 'Admins',
                'count' => $totalAdmins,
                'color' => 'cyan',
            ],
            [
                'label' => 'Organisateurs',
                'count' => $totalOrganizers,
                'color' => 'gold',
            ],
            [
                'label' => 'Jurys',
                'count' => $totalJuries,
                'color' => 'warning',
            ],
            [
                'label' => 'Compétiteurs',
                'count' => $totalCompetitors,
                'color' => 'success',
            ],
        ]);

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalBannedUsers',
            'activeUsers',
            'totalCompetitors', 
            'totalOrganizers',
            'totalAdmins',
            'totalJuries',
            'totalTournaments', 
            'activeTournaments', 
            'completedTournaments',
            'totalPosts',
            'totalComments',
            'totalMatches',
            'totalTeams',
            'totalRegistrations',
            'recentUsers',
            'recentTournaments',
            'recentActivities',
            'userStatusBreakdown',
            'roleBreakdown'
        ));
    }

    private function buildRecentActivities(): Collection
    {
        $roleActivities = DB::table('user_roles')
            ->join('users', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'roles.id', '=', 'user_roles.role_id')
            ->select('users.username', 'roles.name as role_name', 'user_roles.assigned_at')
            ->orderByDesc('user_roles.assigned_at')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'icon' => '🛡️',
                'title' => "{$row->username} a reçu le rôle {$row->role_name}",
                'description' => 'Mise à jour des autorisations administratives.',
                'at' => Carbon::parse($row->assigned_at),
            ]);

        $bannedActivities = User::where('is_banned', true)
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(fn (User $user) => [
                'icon' => '⛔',
                'title' => "{$user->username} est actuellement banni",
                'description' => $user->banned_reason ?: 'Compte suspendu par un administrateur.',
                'at' => $user->updated_at,
            ]);

        $userActivities = User::latest()
            ->take(5)
            ->get()
            ->map(fn (User $user) => [
                'icon' => '👤',
                'title' => "{$user->username} a rejoint la plateforme",
                'description' => $user->email,
                'at' => $user->created_at,
            ]);

        $tournamentActivities = Tournament::with('organizer')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Tournament $tournament) => [
                'icon' => '🏆',
                'title' => "Tournoi créé : {$tournament->title}",
                'description' => 'Organisateur : ' . ($tournament->organizer->username ?? 'Non assigné'),
                'at' => $tournament->created_at,
            ]);

        $postActivities = Post::with('author')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Post $post) => [
                'icon' => '📝',
                'title' => 'Nouveau post dans le Competition Hub',
                'description' => ($post->author->username ?? 'Auteur inconnu') . ' : ' . str($post->content)->limit(70),
                'at' => $post->created_at,
            ]);

        return collect()
            ->merge($roleActivities)
            ->merge($bannedActivities)
            ->merge($userActivities)
            ->merge($tournamentActivities)
            ->merge($postActivities)
            ->sortByDesc('at')
            ->take(10)
            ->values();
    }
}

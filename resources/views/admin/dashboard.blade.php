@extends('admin.layout')

@section('title', 'Dashboard')
@section('eyebrow', 'Administration')
@section('page-title', 'Tableau de bord')
@section('page-description', 'Vue globale de la plateforme, des utilisateurs, des contenus et des tournois.')
@section('active-tab', 'dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <div class="glass-card p-6 border-t-4 border-t-cyan">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Utilisateurs</div>
            <div class="text-5xl font-display text-white">{{ $totalUsers }}</div>
            <div class="text-sm text-gray-400 mt-2">{{ $activeUsers }} actifs, {{ $totalBannedUsers }} bannis</div>
        </div>
        <div class="glass-card p-6 border-t-4 border-t-gold">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Rôles clés</div>
            <div class="text-5xl font-display text-white">{{ $totalOrganizers }}</div>
            <div class="text-sm text-gray-400 mt-2">{{ $totalAdmins }} admins, {{ $totalCompetitors }} compétiteurs</div>
        </div>
        <div class="glass-card p-6 border-t-4 border-t-success">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Tournois</div>
            <div class="text-5xl font-display text-white">{{ $totalTournaments }}</div>
            <div class="text-sm text-gray-400 mt-2">{{ $activeTournaments }} ouverts, {{ $completedTournaments }} terminés</div>
        </div>
        <div class="glass-card p-6 border-t-4 border-t-crimson">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Contenus</div>
            <div class="text-5xl font-display text-white">{{ $totalPosts }}</div>
            <div class="text-sm text-gray-400 mt-2">{{ $totalComments }} commentaires, {{ $totalMatches }} matchs</div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[1.2fr_0.8fr] gap-6 mb-8">
        <section class="glass-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-display font-bold text-white">Synthèse plateforme</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-cyan hover:text-white transition">Gérer les utilisateurs</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @foreach($userStatusBreakdown as $item)
                    @php
                        $percentage = $totalUsers > 0 ? round(($item['count'] / $totalUsers) * 100) : 0;
                    @endphp
                    <div class="rounded-2xl bg-black/30 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-bold text-white">{{ $item['label'] }}</span>
                            <span class="text-sm text-gray-400">{{ $item['count'] }}</span>
                        </div>
                        <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full {{ $item['color'] === 'success' ? 'bg-success' : 'bg-crimson' }}" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">{{ $percentage }}%</div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($roleBreakdown as $item)
                    @php
                        $percentage = $totalUsers > 0 ? round(($item['count'] / $totalUsers) * 100) : 0;
                        $barClass = match ($item['color']) {
                            'cyan' => 'bg-cyan',
                            'gold' => 'bg-gold',
                            default => 'bg-success',
                        };
                    @endphp
                    <div class="rounded-2xl bg-black/30 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-bold text-white">{{ $item['label'] }}</span>
                            <span class="text-sm text-gray-400">{{ $item['count'] }}</span>
                        </div>
                        <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full {{ $barClass }}" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">{{ $percentage }}% des comptes</div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="glass-card p-6">
            <h2 class="text-2xl font-display font-bold text-white mb-6">Indicateurs additionnels</h2>
            <div class="space-y-4">
                <div class="rounded-2xl bg-black/30 p-4">
                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Compétiteurs</div>
                    <div class="text-3xl font-display text-white">{{ $totalCompetitors }}</div>
                </div>
                <div class="rounded-2xl bg-black/30 p-4">
                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Équipes</div>
                    <div class="text-3xl font-display text-white">{{ $totalTeams }}</div>
                </div>
                <div class="rounded-2xl bg-black/30 p-4">
                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Inscriptions</div>
                    <div class="text-3xl font-display text-white">{{ $totalRegistrations }}</div>
                </div>
                <div class="rounded-2xl bg-black/30 p-4">
                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Publications</div>
                    <div class="text-3xl font-display text-white">{{ $totalPosts }}</div>
                </div>
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[1fr_1fr] gap-6 mb-8">
        <section class="glass-card p-6">
            <h2 class="text-2xl font-display font-bold text-white mb-4">Activité récente</h2>
            
            <div class="space-y-4 max-h-[350px] overflow-y-auto pr-2">
                
                @forelse($recentActivities as $activity)
                    <div class="rounded-2xl bg-black/30 p-4">
                        <div class="flex items-start gap-3">
                            <div class="text-2xl">{{ $activity['icon'] }}</div>
                            <div class="min-w-0">
                                <div class="font-bold text-white">{{ $activity['title'] }}</div>
                                <div class="text-sm text-gray-400 mt-1">{{ $activity['description'] }}</div>
                                <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mt-2">{{ $activity['at']->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-500 text-center py-8">Aucune activité récente disponible.</div>
                @endforelse
            </div>
        </section>

        <section class="glass-card p-6">
            <h2 class="text-2xl font-display font-bold text-white mb-4">Accès rapides</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.users.index') }}" class="rounded-2xl bg-black/30 p-5 hover:border-cyan border border-transparent transition">
                    <div class="text-3xl mb-2">Users</div>
                    <div class="font-bold text-white">Gérer les utilisateurs</div>
                    <div class="text-sm text-gray-400 mt-1">Recherche, filtres, rôles et bannissements.</div>
                </a>
                <a href="{{ route('admin.tournaments.index') }}" class="rounded-2xl bg-black/30 p-5 hover:border-gold border border-transparent transition">
                    <div class="text-3xl mb-2">Cup</div>
                    <div class="font-bold text-white">Superviser les tournois</div>
                    <div class="text-sm text-gray-400 mt-1">Modération, accès rapide aux écrans organisateurs.</div>
                </a>
                <a href="{{ route('admin.tournaments.create') }}" class="rounded-2xl bg-black/30 p-5 hover:border-success border border-transparent transition">
                    <div class="text-3xl mb-2">+</div>
                    <div class="font-bold text-white">Créer un tournoi</div>
                    <div class="text-sm text-gray-400 mt-1">Création centralisée avec organisateur assigné.</div>
                </a>
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <section class="glass-card p-6 overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-display font-bold text-white">Derniers utilisateurs</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-cyan hover:text-white transition">Voir tout</a>
            </div>
            <table class="w-full min-w-[520px] text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-[0.25em] text-gray-500 border-b border-white/10">
                        <th class="pb-3">Utilisateur</th>
                        <th class="pb-3">Rôle</th>
                        <th class="pb-3">Statut</th>
                        <th class="pb-3">Créé</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                        <tr class="border-b border-white/5">
                            <td class="py-3">
                                <div class="font-bold text-white">{{ $user->username }}</div>
                                <div class="text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="py-3">{{ $user->primaryRoleName() }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $user->is_banned ? 'bg-crimson/10 text-crimson' : 'bg-success/10 text-success' }}">
                                    {{ $user->is_banned ? 'Banni' : 'Actif' }}
                                </span>
                            </td>
                            <td class="py-3 text-gray-400">{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="glass-card p-6 overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-display font-bold text-white">Derniers tournois</h2>
                <a href="{{ route('admin.tournaments.index') }}" class="text-sm font-bold text-gold hover:text-white transition">Voir tout</a>
            </div>
            <table class="w-full min-w-[520px] text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-[0.25em] text-gray-500 border-b border-white/10">
                        <th class="pb-3">Tournoi</th>
                        <th class="pb-3">Jeu</th>
                        <th class="pb-3">Organisateur</th>
                        <th class="pb-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTournaments as $tournament)
                        <tr class="border-b border-white/5">
                            <td class="py-3 font-bold text-white">{{ $tournament->title }}</td>
                            <td class="py-3 text-gray-400">{{ $tournament->game->name ?? 'Jeu inconnu' }}</td>
                            <td class="py-3 text-gray-400">{{ $tournament->organizer->username ?? 'Non assigné' }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $tournament->status === 'Ouvert' ? 'bg-success/10 text-success' : 'bg-white/5 text-gray-300' }}">
                                    {{ $tournament->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
@endsection

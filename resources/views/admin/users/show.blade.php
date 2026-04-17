@extends('admin.layout')

@section('title', 'Fiche utilisateur')
@section('eyebrow', 'Administration')
@section('page-title', 'Fiche utilisateur')
@section('page-description', 'Détails complets du compte, statistiques et actions administratives.')
@section('active-tab', 'users')

@section('content')
    @php
        $roleName = $user->primaryRoleName();
        $isCurrentAdmin = $user->id === auth()->id();
        $isProtectedAdmin = $user->hasRole('Admin');
    @endphp

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-cyan hover:text-white transition">← Retour à la liste</a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[1.1fr_0.9fr] gap-6">
        <section class="glass-card p-6">
            <div class="flex flex-col md:flex-row md:items-center gap-5 mb-6">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=111827&color=fff&size=160" class="w-24 h-24 rounded-full border border-white/10" alt="{{ $user->username }}">
                <div>
                    <h2 class="text-4xl font-display font-bold text-white">{{ $user->username }}</h2>
                    <p class="text-gray-400">{{ $user->email }}</p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $user->is_banned ? 'bg-crimson/10 text-crimson' : 'bg-success/10 text-success' }}">
                            {{ $user->is_banned ? 'Banni' : 'Actif' }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $roleName === 'Admin' ? 'bg-cyan/10 text-cyan' : ($roleName === 'Organisateur' ? 'bg-gold/10 text-gold' : 'bg-white/5 text-gray-300') }}">
                            {{ $roleName }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-2xl bg-black/30 p-4">
                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">ID</div>
                    <div class="font-bold text-white">#{{ $user->id }}</div>
                </div>
                <div class="rounded-2xl bg-black/30 p-4">
                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Créé le</div>
                    <div class="font-bold text-white">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="rounded-2xl bg-black/30 p-4">
                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Dernière connexion</div>
                    <div class="font-bold text-white">{{ $user->last_login?->format('d/m/Y H:i') ?? 'Jamais' }}</div>
                </div>
                <div class="rounded-2xl bg-black/30 p-4">
                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Banni par</div>
                    <div class="font-bold text-white">{{ $user->banned_by ?: 'N/A' }}</div>
                </div>
                <div class="rounded-2xl bg-black/30 p-4 md:col-span-2">
                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Raison</div>
                    <div class="font-bold text-white">{{ $user->banned_reason ?: 'Aucune raison enregistrée.' }}</div>
                </div>
            </div>
        </section>

        <section class="glass-card p-6">
            <h2 class="text-2xl font-display font-bold text-white mb-4">Actions administratives</h2>

            @if($isCurrentAdmin)
                <div class="rounded-2xl border border-cyan/20 bg-cyan/10 p-4 text-cyan font-bold">
                    Ce compte correspond à ta session actuelle. Les actions sensibles sont désactivées.
                </div>
            @elseif($isProtectedAdmin)
                <div class="rounded-2xl border border-cyan/20 bg-cyan/10 p-4 text-cyan font-bold">
                    Ce compte administrateur est protégé contre le bannissement et la modification de rôle depuis cette interface.
                </div>
            @else
                <form action="{{ route('admin.users.change_role', $user) }}" method="POST" class="space-y-4 mb-6" onsubmit="return confirm('Changer le rôle de {{ $user->username }} ?');">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Rôle principal</label>
                        <select name="role" class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
                            <option value="Compétiteur" @selected($roleName === 'Compétiteur')>Compétiteur</option>
                            <option value="Organisateur" @selected($roleName === 'Organisateur')>Organisateur</option>
                            <option value="Jury" @selected($roleName === 'Jury')>Jury</option>
                        </select>
                    </div>
                    <button type="submit" class="rounded-xl bg-cyan hover:bg-[#00d7e6] text-black px-5 py-3 font-bold transition">
                        Mettre à jour le rôle
                    </button>
                </form>

                <form action="{{ route('admin.users.toggle_ban', $user) }}" method="POST" onsubmit="return confirm('{{ $user->is_banned ? "Débannir" : "Bannir" }} {{ $user->username }} ?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="rounded-xl px-5 py-3 font-bold transition {{ $user->is_banned ? 'bg-success hover:bg-green-500 text-white' : 'bg-crimson hover:bg-red-700 text-white' }}">
                        {{ $user->is_banned ? 'Débannir ce compte' : 'Bannir ce compte' }}
                    </button>
                </form>
            @endif
        </section>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mt-6">
        <div class="glass-card p-5">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Inscriptions</div>
            <div class="text-4xl font-display text-white">{{ $userStats['registrations_count'] }}</div>
        </div>
        <div class="glass-card p-5">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Équipes</div>
            <div class="text-4xl font-display text-white">{{ $userStats['teams_count'] }}</div>
        </div>
        <div class="glass-card p-5">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Tournois organisés</div>
            <div class="text-4xl font-display text-white">{{ $userStats['organized_tournaments_count'] }}</div>
        </div>
        <div class="glass-card p-5">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Matchs gagnés</div>
            <div class="text-4xl font-display text-white">{{ $userStats['won_matches_count'] }}</div>
        </div>
        <div class="glass-card p-5">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Posts</div>
            <div class="text-4xl font-display text-white">{{ $userStats['posts_count'] }}</div>
        </div>
        <div class="glass-card p-5">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Commentaires</div>
            <div class="text-4xl font-display text-white">{{ $userStats['comments_count'] }}</div>
        </div>
    </div>
@endsection

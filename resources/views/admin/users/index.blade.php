@extends('admin.layout')

@section('title', 'Utilisateurs')
@section('eyebrow', 'Administration')
@section('page-title', 'Gestion des utilisateurs')
@section('page-description', 'Recherche, filtres, détails, changement de rôle et bannissement sécurisé.')
@section('active-tab', 'users')

@section('content')
    <section class="glass-card p-6 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[2fr_1fr_1fr_160px_auto] gap-4">
            <div>
                <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Recherche</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="Nom, email ou ID" class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Rôle</label>
                <select name="role" class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
                    <option value="">Tous</option>
                    @foreach($roleOptions as $option)
                        <option value="{{ $option }}" @selected($role === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Statut</label>
                <select name="status" class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
                    <option value="">Tous</option>
                    <option value="active" @selected($status === 'active')>Actifs</option>
                    <option value="banned" @selected($status === 'banned')>Bannis</option>
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Par page</label>
                <select name="per_page" class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
                    @foreach([10, 25, 50] as $option)
                        <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="rounded-xl bg-cyan hover:bg-[#00d7e6] text-black px-5 py-3 font-bold transition">Filtrer</button>
                <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-white/10 px-5 py-3 font-bold text-gray-300 hover:text-white hover:bg-white/5 transition">Reset</a>
            </div>
        </form>
    </section>

    <section class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1180px] text-sm">
                <thead class="bg-black/20">
                    <tr class="text-left text-xs uppercase tracking-[0.25em] text-gray-500">
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Utilisateur</th>
                        <th class="px-5 py-4">Créé le</th>
                        <th class="px-5 py-4">Badges</th>
                        <th class="px-5 py-4">Détails</th>
                        <th class="px-5 py-4">Rôle</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $roleName = $user->primaryRoleName();
                            $isCurrentAdmin = $user->id === auth()->id();
                            $isProtectedAdmin = $user->hasRole('Admin');
                        @endphp
                        <tr class="border-t border-white/5 hover:bg-white/5 transition">
                            <td class="px-5 py-4 font-bold text-gray-400">#{{ $user->id }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=111827&color=fff" class="w-10 h-10 rounded-full border border-white/10" alt="{{ $user->username }}">
                                    <div>
                                        <div class="font-bold text-white">{{ $user->username }}</div>
                                        <div class="text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-400">
                                <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs uppercase tracking-[0.2em] text-gray-600">{{ $user->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $user->is_banned ? 'bg-crimson/10 text-crimson' : 'bg-success/10 text-success' }}">
                                        {{ $user->is_banned ? 'Banni' : 'Actif' }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $roleName === 'Admin' ? 'bg-cyan/10 text-cyan' : ($roleName === 'Organisateur' ? 'bg-gold/10 text-gold' : 'bg-white/5 text-gray-300') }}">
                                        {{ $roleName }}
                                    </span>
                                    @if($isCurrentAdmin)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-white">Session actuelle</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center rounded-xl border border-white/10 px-4 py-2 font-bold text-cyan hover:border-cyan hover:text-white transition">
                                    Voir la fiche
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                @if($isProtectedAdmin)
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Compte protégé</div>
                                @else
                                    <form action="{{ route('admin.users.change_role', $user) }}" method="POST" class="flex items-center gap-2" onsubmit="return confirm('Changer le rôle de {{ $user->username }} ?');">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="rounded-xl bg-black/40 border border-white/10 px-3 py-2 text-white">
                                            <option value="Compétiteur" @selected($roleName === 'Compétiteur')>Compétiteur</option>
                                            <option value="Organisateur" @selected($roleName === 'Organisateur')>Organisateur</option>
                                            <option value="Jury" @selected($roleName === 'Jury')>Jury</option>
                                        </select>
                                        <button type="submit" class="rounded-xl bg-white/5 border border-white/10 px-3 py-2 font-bold text-gray-200 hover:bg-white/10 transition">
                                            Sauver
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($isCurrentAdmin)
                                    <span class="text-xs uppercase tracking-[0.25em] text-gray-500">Action bloquée</span>
                                @elseif($isProtectedAdmin)
                                    <span class="text-xs uppercase tracking-[0.25em] text-gray-500">Admin protégé</span>
                                @else
                                    <form action="{{ route('admin.users.toggle_ban', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ $user->is_banned ? "Débannir" : "Bannir" }} {{ $user->username }} ?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-xl px-4 py-2 font-bold transition {{ $user->is_banned ? 'bg-success/10 border border-success/30 text-success hover:bg-success hover:text-white' : 'bg-crimson/10 border border-crimson/30 text-crimson hover:bg-crimson hover:text-white' }}">
                                            {{ $user->is_banned ? 'Débannir' : 'Bannir' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-500">Aucun utilisateur ne correspond aux filtres actuels.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-white/5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="text-sm text-gray-500">
                Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </section>
@endsection

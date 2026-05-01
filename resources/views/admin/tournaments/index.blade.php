@extends('admin.layout')

@section('title', 'Tournois')
@section('eyebrow', 'Administration')
@section('page-title', 'Supervision des tournois')
@section('page-description', 'Modération globale, filtres, accès rapide aux écrans de gestion et suppression sécurisée.')
@section('active-tab', 'tournaments')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="glass-card p-5 border-t-4 border-t-cyan">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Total</div>
            <div class="text-4xl font-display text-white">{{ $tournamentStats['total'] }}</div>
        </div>
        <div class="glass-card p-5 border-t-4 border-t-success">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Ouverts</div>
            <div class="text-4xl font-display text-white">{{ $tournamentStats['open'] }}</div>
        </div>
        <div class="glass-card p-5 border-t-4 border-t-warning">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">À venir</div>
            <div class="text-4xl font-display text-white">{{ $tournamentStats['upcoming'] }}</div>
        </div>
        <div class="glass-card p-5 border-t-4 border-t-gold">
            <div class="text-xs uppercase tracking-[0.25em] text-gray-500 mb-1">Terminés</div>
            <div class="text-4xl font-display text-white">{{ $tournamentStats['completed'] }}</div>
        </div>
    </div>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.tournaments.create') }}" class="rounded-xl bg-cyan hover:bg-[#00d7e6] text-black px-5 py-3 font-bold transition">
            Nouveau tournoi
        </a>
    </div>

    <section class="glass-card p-6 mb-6">
        <form method="GET" action="{{ route('admin.tournaments.index') }}" class="grid grid-cols-1 md:grid-cols-[2fr_1fr_160px_auto] gap-4">
            <div>
                <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Recherche</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="Titre, jeu ou organisateur" class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-[0.25em] text-gray-500 mb-2">Statut</label>
                <select name="status" class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
                    <option value="">Tous</option>
                    @foreach(['Ouvert', 'Fermé', 'À venir', 'Terminé'] as $option)
                        <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                    @endforeach
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
                <a href="{{ route('admin.tournaments.index') }}" class="rounded-xl border border-white/10 px-5 py-3 font-bold text-gray-300 hover:text-white hover:bg-white/5 transition">Reset</a>
            </div>
        </form>
    </section>

    <section class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1150px] text-sm">
                <thead class="bg-black/20">
                    <tr class="text-left text-xs uppercase tracking-[0.25em] text-gray-500">
                        <th class="px-5 py-4">Tournoi</th>
                        <th class="px-5 py-4">Jeu</th>
                        <th class="px-5 py-4">Organisateur</th>
                        <th class="px-5 py-4">Participants</th>
                        <th class="px-5 py-4">Statut</th>
                        <th class="px-5 py-4">Date</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tournaments as $tournament)
                        <tr class="border-t border-white/5 hover:bg-white/5 transition">
                            <td class="px-5 py-4">
                                <div class="font-bold text-white">{{ $tournament->title }}</div>
                                <div class="text-xs uppercase tracking-[0.25em] text-gray-600">#{{ $tournament->id }}</div>
                            </td>
                            <td class="px-5 py-4 text-gray-300">{{ $tournament->game->name ?? 'Jeu inconnu' }}</td>
                            <td class="px-5 py-4 text-gray-300">{{ $tournament->organizer->username ?? 'Non assigné' }}</td>
                            <td class="px-5 py-4 text-gray-300">
                                {{ $tournament->confirmed_registrations_count }}/{{ $tournament->max_capacity }}
                                @if($tournament->pending_registrations_count > 0)
                                    <div class="text-xs text-warning font-bold mt-1">{{ $tournament->pending_registrations_count }} en attente</div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $tournament->status === 'Ouvert' ? 'bg-success/10 text-success' : ($tournament->status === 'Terminé' ? 'bg-gold/10 text-gold' : 'bg-white/5 text-gray-300') }}">
                                    {{ $tournament->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-400">{{ $tournament->event_date->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="rounded-xl border border-cyan/20 bg-cyan/10 px-4 py-2 font-bold text-cyan hover:bg-cyan hover:text-black transition">
                                        Modifier
                                    </a>
                                    <a href="{{ route('organizer.tournaments.show', $tournament) }}" class="rounded-xl border border-success/20 bg-success/10 px-4 py-2 font-bold text-success hover:bg-success hover:text-black transition">
                                        Inscriptions
                                    </a>
                                    <a href="{{ route('organizer.matches.index', $tournament) }}" class="rounded-xl border border-gold/20 bg-gold/10 px-4 py-2 font-bold text-gold hover:bg-gold hover:text-black transition">
                                        Arbre & matchs
                                    </a>
                                    <form action="{{ route('admin.tournaments.destroy', $tournament) }}" method="POST" onsubmit="return confirm('Supprimer définitivement {{ $tournament->title }} ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-xl border border-crimson/30 bg-crimson/10 px-4 py-2 font-bold text-crimson hover:bg-crimson hover:text-white transition">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-500">Aucun tournoi ne correspond aux filtres.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-white/5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="text-sm text-gray-500">
                Affichage de {{ $tournaments->firstItem() ?? 0 }} à {{ $tournaments->lastItem() ?? 0 }} sur {{ $tournaments->total() }} tournois
            </div>
            <div>
                {{ $tournaments->links() }}
            </div>
        </div>
    </section>
@endsection

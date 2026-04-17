<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->title }} - YouCode Arena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#050505',
                        crimson: '#DC143C',
                        cyan: '#00F0FF',
                        gold: '#FFD700',
                        success: '#22C55E'
                    },
                    fontFamily: {
                        display: ['Teko', 'sans-serif'],
                        sans: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #050505;
            background-image: radial-gradient(circle at 20% 20%, rgba(220, 20, 60, 0.08) 0%, transparent 30%),
                radial-gradient(circle at 80% 20%, rgba(0, 240, 255, 0.06) 0%, transparent 30%);
        }
        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.78), rgba(10, 12, 18, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="text-gray-200 font-sans min-h-screen">
    <main class="max-w-7xl mx-auto px-6 py-10">
        <a href="{{ route('competitor.tournaments.index') }}" class="text-sm text-gray-500 hover:text-white transition">← Retour aux tournois</a>

        @if(session('success'))
            <div class="mt-6 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-success font-bold">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mt-6 rounded-xl border border-crimson/30 bg-crimson/10 px-4 py-3 text-crimson font-bold">{{ session('error') }}</div>
        @endif

        <section class="glass-card rounded-3xl mt-6 overflow-hidden">
            <div class="p-8 md:p-10 border-b border-white/5">
                <div class="flex flex-col lg:flex-row justify-between gap-8">
                    <div>
                        <div class="flex flex-wrap gap-3 mb-4">
                            <span class="px-3 py-1 rounded-full text-xs uppercase tracking-widest bg-gold/10 text-gold font-bold">{{ $tournament->game->name }}</span>
                            <span class="px-3 py-1 rounded-full text-xs uppercase tracking-widest bg-white/5 text-gray-300 font-bold">{{ $tournament->status }}</span>
                            <span class="px-3 py-1 rounded-full text-xs uppercase tracking-widest bg-cyan/10 text-cyan font-bold">{{ $tournament->category->name ?? 'Catégorie' }}</span>
                        </div>
                        <h1 class="text-5xl font-display font-bold text-white tracking-wide">{{ $tournament->title }}</h1>
                        <p class="text-gray-400 mt-3 max-w-2xl">
                            Suis les inscriptions, découvre tes adversaires, consulte tes prochaines rencontres et surveille l'arbre du tournoi.
                        </p>
                    </div>

                    <div class="min-w-[280px] space-y-4">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-2xl bg-black/30 p-4">
                                <div class="text-xs uppercase tracking-widest text-gray-500 mb-1">Date</div>
                                <div class="font-bold text-white">{{ $tournament->event_date->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="rounded-2xl bg-black/30 p-4">
                                <div class="text-xs uppercase tracking-widest text-gray-500 mb-1">Places</div>
                                <div class="font-bold text-white">{{ $tournament->registered_count }}/{{ $tournament->max_capacity }}</div>
                            </div>
                        </div>

                        @if($isRegistered)
                            <div class="rounded-2xl border border-success/30 bg-success/10 px-4 py-3 text-success font-bold">
                                Tu es inscrit
                                @if($registration)
                                    <span class="text-sm text-gray-300 block mt-1">Statut : {{ $registration->status }}</span>
                                @endif
                            </div>

                            <form action="{{ route('competitor.tournaments.leave', $tournament) }}" method="POST" onsubmit="return confirm('Quitter ce tournoi et libérer ta place ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded-xl bg-crimson hover:bg-red-700 text-white font-display tracking-widest py-3 transition">
                                    QUITTER LE TOURNOI
                                </button>
                            </form>
                        @elseif($canJoin)
                            <a href="{{ route('competitor.teams.create', $tournament) }}" class="block w-full rounded-xl bg-crimson hover:bg-red-700 text-center text-white font-display tracking-widest py-3 transition">
                                {{ $tournament->game?->requiresTeamInvite() ? "CRÉER L'ÉQUIPE" : 'REJOINDRE LE TOURNOI' }}
                            </a>
                        @else
                            <div class="rounded-2xl bg-white/5 px-4 py-3 text-gray-400 font-bold text-center">
                                Inscriptions non disponibles actuellement
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="glass-card rounded-2xl p-6">
                        <h2 class="text-2xl font-display font-bold text-white mb-4">Vue d'ensemble</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="rounded-xl bg-black/30 p-4">
                                <div class="text-xs uppercase tracking-widest text-gray-500 mb-1">Organisateur</div>
                                <div class="font-bold text-white">{{ $tournament->organizer->username ?? 'Non défini' }}</div>
                            </div>
                            <div class="rounded-xl bg-black/30 p-4">
                                <div class="text-xs uppercase tracking-widest text-gray-500 mb-1">Format</div>
                                <div class="font-bold text-white">{{ $tournament->game?->requiresTeamInvite() ? 'Duo / équipe' : 'Solo' }}</div>
                            </div>
                            <div class="rounded-xl bg-black/30 p-4">
                                <div class="text-xs uppercase tracking-widest text-gray-500 mb-1">Compétiteurs / équipes</div>
                                <div class="font-bold text-white">{{ $tournament->teams->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-2xl p-6">
                        <h2 class="text-2xl font-display font-bold text-white mb-4">Challenge Cards</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($challengeCards as $match)
                                @php
                                    $isMyTeamOne = $userTeam && $match->team1_id === $userTeam->id;
                                    $myTeamName = $isMyTeamOne ? $match->team1->name : ($match->team2->name ?? 'Mon équipe');
                                    $opponentName = $isMyTeamOne ? ($match->team2->name ?? 'TBD') : ($match->team1->name ?? 'TBD');
                                @endphp
                                <div class="rounded-2xl bg-black/30 p-5 border border-white/5">
                                    <p class="text-xs uppercase tracking-[0.3em] text-gold mb-2">Round {{ $match->round ?? 1 }}</p>
                                    <h3 class="text-2xl font-display font-bold text-white">{{ $myTeamName }} <span class="text-crimson">vs</span> {{ $opponentName }}</h3>
                                    <p class="text-sm text-gray-400 mt-2">{{ optional($match->played_at)->format('d/m/Y H:i') ?? 'Horaire à définir' }}</p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="text-sm font-bold {{ $match->status === 'Terminé' ? 'text-success' : 'text-cyan' }}">{{ $match->status }}</span>
                                        @if($match->score)
                                            <span class="text-white font-bold">{{ $match->score }}</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="md:col-span-2 rounded-2xl border border-dashed border-white/10 p-6 text-center text-gray-500">
                                    Aucun challenge n'est encore lié à ton parcours sur ce tournoi.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="glass-card rounded-2xl p-6">
                        <h2 class="text-2xl font-display font-bold text-white mb-4">Arbre du tournoi</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                            @forelse($bracketRounds as $roundNumber => $roundMatches)
                                <div class="rounded-2xl bg-black/30 p-4">
                                    <div class="text-xs uppercase tracking-[0.3em] text-cyan mb-3">Round {{ $roundNumber }}</div>
                                    <div class="space-y-3">
                                        @foreach($roundMatches as $match)
                                            <div class="rounded-xl bg-white/5 p-3 border border-white/5">
                                                <div class="font-bold text-white">{{ $match->team1->name ?? 'TBD' }}</div>
                                                <div class="text-sm text-gray-500 my-1">vs</div>
                                                <div class="font-bold text-white">{{ $match->team2->name ?? 'TBD' }}</div>
                                                <div class="mt-2 text-xs uppercase tracking-widest {{ $match->status === 'Terminé' ? 'text-success' : 'text-gray-400' }}">
                                                    {{ $match->status }}{{ $match->score ? ' • ' . $match->score : '' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-white/10 p-6 text-center text-gray-500">
                                    L'arbre n'a pas encore été configuré par l'organisateur.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="glass-card rounded-2xl p-6">
                        <h2 class="text-2xl font-display font-bold text-white mb-4">Participants</h2>
                        <div class="space-y-3 max-h-[420px] overflow-auto pr-1">
                            @forelse($tournament->teams as $team)
                                <div class="rounded-xl bg-black/30 p-4 border {{ $userTeam && $userTeam->id === $team->id ? 'border-crimson/40' : 'border-white/5' }}">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <div class="font-bold text-white">{{ $team->name }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $team->members->pluck('username')->implode(', ') ?: 'Membre à confirmer' }}</div>
                                        </div>
                                        @if($userTeam && $userTeam->id === $team->id)
                                            <span class="text-xs uppercase tracking-widest text-crimson font-bold">Toi</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border border-dashed border-white/10 p-4 text-gray-500 text-center">
                                    Aucun participant pour le moment.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="glass-card rounded-2xl p-6">
                        <h2 class="text-2xl font-display font-bold text-white mb-4">Prochain match</h2>
                        @if($nextMatch)
                            @php
                                $myFirst = $userTeam && $nextMatch->team1_id === $userTeam->id;
                                $opponent = $myFirst ? $nextMatch->team2 : $nextMatch->team1;
                            @endphp
                            <div class="rounded-2xl bg-black/30 p-5">
                                <p class="text-xs uppercase tracking-[0.3em] text-gold mb-2">À surveiller</p>
                                <h3 class="text-3xl font-display font-bold text-white">{{ $userTeam?->name ?? 'Mon équipe' }}</h3>
                                <p class="text-crimson font-display text-2xl tracking-widest">VS {{ $opponent->name ?? 'TBD' }}</p>
                                <p class="text-sm text-gray-400 mt-3">{{ optional($nextMatch->played_at)->format('d/m/Y H:i') ?? 'Horaire à définir' }}</p>
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-white/10 p-5 text-gray-500">
                                Aucun prochain match n'est encore planifié.
                            </div>
                        @endif
                    </div>
                </aside>
            </div>
        </section>
    </main>
</body>
</html>

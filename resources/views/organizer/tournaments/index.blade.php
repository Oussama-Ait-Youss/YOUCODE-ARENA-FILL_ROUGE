<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Tournois - YouCode Arena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#050505',
                        gold: '#FFD700',
                        cyan: '#00F0FF',
                        crimson: '#DC143C',
                        success: '#22C55E',
                        warning: '#f59e0b'
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
            background-image: radial-gradient(circle at 50% 10%, rgba(255, 215, 0, 0.05) 0%, transparent 40%);
        }
        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.8), rgba(10, 12, 18, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="text-gray-200 font-sans min-h-screen">
    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-8">
            <div>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gold transition">← Retour à l'espace organisateur</a>
                <h1 class="text-5xl font-display font-bold text-white tracking-wider mt-2">MES TOURNOIS</h1>
                <p class="text-gray-400">Vue opérationnelle de tes compétitions, inscriptions et matchs.</p>
            </div>
            <a href="{{ route('organizer.tournaments.create') }}" class="inline-flex items-center justify-center bg-gold hover:bg-yellow-500 text-black font-bold px-5 py-3 rounded-lg transition">
                + Créer un tournoi
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-success font-bold">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl border border-crimson/30 bg-crimson/10 px-4 py-3 text-crimson font-bold">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($tournaments as $tournament)
                <article class="glass-card rounded-2xl p-6 flex flex-col gap-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-gold text-sm uppercase tracking-[0.3em] font-bold">{{ $tournament->game->name ?? 'Jeu' }}</p>
                            <h2 class="text-3xl font-display font-bold text-white">{{ $tournament->title }}</h2>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest border {{ $tournament->status === 'Ouvert' ? 'border-success/40 text-success bg-success/10' : 'border-white/10 text-gray-300 bg-white/5' }}">
                            {{ $tournament->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl bg-black/30 p-4">
                            <div class="text-gray-500 uppercase tracking-widest text-xs mb-1">Participants confirmés</div>
                            <div class="text-2xl font-display text-white">{{ $tournament->confirmed_registrations_count }}/{{ $tournament->max_capacity }}</div>
                        </div>
                        <div class="rounded-xl bg-black/30 p-4">
                            <div class="text-gray-500 uppercase tracking-widest text-xs mb-1">Demandes en attente</div>
                            <div class="text-2xl font-display text-warning">{{ $tournament->pending_registrations_count }}</div>
                        </div>
                        <div class="rounded-xl bg-black/30 p-4">
                            <div class="text-gray-500 uppercase tracking-widest text-xs mb-1">Matchs planifiés</div>
                            <div class="text-2xl font-display text-cyan">{{ $tournament->scheduled_matches_count }}</div>
                        </div>
                        <div class="rounded-xl bg-black/30 p-4">
                            <div class="text-gray-500 uppercase tracking-widest text-xs mb-1">Date</div>
                            <div class="text-lg font-bold text-white">{{ $tournament->event_date->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('organizer.tournaments.edit', $tournament) }}" class="px-4 py-2 rounded-lg bg-cyan/10 text-cyan border border-cyan/20 hover:bg-cyan hover:text-black transition font-bold text-sm">
                            Modifier
                        </a>
                        <a href="{{ route('organizer.matches.index', $tournament) }}" class="px-4 py-2 rounded-lg bg-white/5 text-white border border-white/10 hover:border-gold hover:text-gold transition font-bold text-sm">
                            Matchs & arbre
                        </a>
                        <form action="{{ route('organizer.tournaments.update_status', $tournament) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="rounded-lg bg-black/40 border border-white/10 px-3 py-2 text-sm text-white">
                                @foreach(['Ouvert', 'Fermé', 'À venir', 'Terminé'] as $status)
                                    <option value="{{ $status }}" @selected($tournament->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-gold text-black hover:bg-yellow-500 transition font-bold text-sm">
                                Mettre à jour
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="col-span-full glass-card rounded-2xl p-12 text-center text-gray-400">
                    <p class="text-3xl font-display font-bold text-white mb-2">Aucun tournoi pour le moment</p>
                    <p>Crée ton premier tournoi pour ouvrir l'arène.</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>

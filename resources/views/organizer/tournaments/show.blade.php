<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptions - {{ $tournament->title }}</title>
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
            background-image: radial-gradient(circle at 50% 10%, rgba(0, 240, 255, 0.05) 0%, transparent 42%);
        }

        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.8), rgba(10, 12, 18, 0.92));
            border: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="text-gray-200 font-sans min-h-screen">
    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="mb-8">
            @if(auth()->user()->hasRole('Admin'))
                <a href="{{ route('admin.tournaments.index') }}" class="text-sm text-gray-500 hover:text-cyan transition">Retour administration</a>
            @else
                <a href="{{ route('organizer.tournaments.index') }}" class="text-sm text-gray-500 hover:text-gold transition">Retour à mes tournois</a>
            @endif

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mt-4">
                <div>
                    <p class="text-cyan text-sm uppercase tracking-[0.3em] font-bold">{{ $tournament->game->name ?? 'Jeu' }}</p>
                    <h1 class="text-5xl font-display font-bold text-white tracking-wider">{{ $tournament->title }}</h1>
                    <p class="text-gray-400 mt-2">
                        {{ $tournament->organizer->username ?? 'Organisateur inconnu' }} · {{ $tournament->status }} · {{ $tournament->event_date->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 min-w-[360px]">
                    <div class="glass-card rounded-xl p-4">
                        <div class="text-xs uppercase tracking-widest text-gray-500">Confirmés</div>
                        <div class="text-3xl font-display text-success">{{ $confirmedRegistrations->count() }}</div>
                    </div>
                    <div class="glass-card rounded-xl p-4">
                        <div class="text-xs uppercase tracking-widest text-gray-500">En attente</div>
                        <div class="text-3xl font-display text-warning">{{ $pendingRegistrations->count() }}</div>
                    </div>
                    <div class="glass-card rounded-xl p-4">
                        <div class="text-xs uppercase tracking-widest text-gray-500">Capacité</div>
                        <div class="text-3xl font-display text-white">{{ $tournament->registered_count }}/{{ $tournament->max_capacity }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-success font-bold">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl border border-crimson/30 bg-crimson/10 px-4 py-3 text-crimson font-bold">{{ session('error') }}</div>
        @endif

        <section class="glass-card rounded-2xl overflow-hidden mb-8">
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <h2 class="text-3xl font-display font-bold text-white">Demandes à valider</h2>
                <span class="text-warning font-bold">{{ $pendingRegistrations->count() }} en attente</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-sm">
                    <thead class="bg-black/20 text-left text-xs uppercase tracking-[0.25em] text-gray-500">
                        <tr>
                            <th class="px-5 py-4">Joueur</th>
                            <th class="px-5 py-4">Équipe</th>
                            <th class="px-5 py-4">Type</th>
                            <th class="px-5 py-4">Date</th>
                            <th class="px-5 py-4 text-right">Décision</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingRegistrations as $registration)
                            @php
                                $isTeamMember = $registration->team
                                    ? $registration->team->members->contains('id', $registration->user_id)
                                    : false;
                            @endphp
                            <tr class="border-t border-white/5">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-white">{{ $registration->user->username ?? 'Utilisateur supprimé' }}</div>
                                    <div class="text-gray-500">{{ $registration->user->email ?? '' }}</div>
                                </td>
                                <td class="px-5 py-4 text-gray-300">
                                    {{ $registration->team->name ?? 'Solo' }}
                                    @if($registration->team)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Membres : {{ $registration->team->members->pluck('username')->implode(', ') ?: 'Invitation non acceptée' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if($registration->team_id && !$isTeamMember)
                                        <span class="px-3 py-1 rounded-full bg-warning/10 text-warning font-bold text-xs">Invitation non acceptée</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-cyan/10 text-cyan font-bold text-xs">Prêt pour validation</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-400">{{ $registration->registration_date?->format('d/m/Y H:i') ?? $registration->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('organizer.tournaments.participants.accept', [$tournament, $registration]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-xl bg-success/10 border border-success/30 text-success px-4 py-2 font-bold hover:bg-success hover:text-black transition">
                                                Accepter
                                            </button>
                                        </form>
                                        <form action="{{ route('organizer.tournaments.participants.reject', [$tournament, $registration]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-xl bg-crimson/10 border border-crimson/30 text-crimson px-4 py-2 font-bold hover:bg-crimson hover:text-white transition">
                                                Refuser
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-500">Aucune demande en attente pour ce tournoi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-3xl font-display font-bold text-white mb-4">Participants confirmés</h2>
                <div class="space-y-3">
                    @forelse($confirmedRegistrations as $registration)
                        <div class="rounded-xl bg-black/30 p-4 flex items-center justify-between gap-4">
                            <div>
                                <div class="font-bold text-white">{{ $registration->user->username ?? 'Utilisateur supprimé' }}</div>
                                <div class="text-xs text-gray-500">{{ $registration->team->name ?? 'Solo' }}</div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-success/10 text-success font-bold text-xs">Confirmé</span>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-white/10 p-6 text-center text-gray-500">Aucun participant confirmé.</div>
                    @endforelse
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6">
                <h2 class="text-3xl font-display font-bold text-white mb-4">Refusés</h2>
                <div class="space-y-3">
                    @forelse($rejectedRegistrations as $registration)
                        <div class="rounded-xl bg-black/30 p-4 flex items-center justify-between gap-4">
                            <div>
                                <div class="font-bold text-white">{{ $registration->user->username ?? 'Utilisateur supprimé' }}</div>
                                <div class="text-xs text-gray-500">{{ $registration->team->name ?? 'Solo' }}</div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-crimson/10 text-crimson font-bold text-xs">Refusé</span>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-white/10 p-6 text-center text-gray-500">Aucune inscription refusée.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
</body>
</html>

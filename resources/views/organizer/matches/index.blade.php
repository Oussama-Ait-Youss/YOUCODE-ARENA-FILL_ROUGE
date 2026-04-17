<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matchs & Arbre - YouCode Arena</title>
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
            background-image:
                radial-gradient(circle at 15% 20%, rgba(255, 215, 0, 0.06) 0%, transparent 30%),
                radial-gradient(circle at 85% 10%, rgba(0, 240, 255, 0.05) 0%, transparent 30%);
        }

        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.78), rgba(10, 12, 18, 0.92));
            border: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
        }

        .bracket-canvas {
            background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 70%, #f3f3f3 100%);
            color: #111827;
            border-radius: 30px;
            padding: 3rem 2.5rem;
            overflow-x: auto;
            position: relative;
            border: 1px solid #e5e7eb;
        }

        .bracket-board {
            display: flex;
            gap: 56px;
            min-width: max-content;
        }

        .round-column {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            min-height: calc(var(--round-one-matches) * 180px);
            gap: 28px;
        }

        .round-label {
            font-size: 0.8rem;
            letter-spacing: 0.35em;
            font-weight: 800;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .bracket-match {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 14px;
            min-width: 190px;
        }

        .bracket-match.has-next .match-connector {
            position: absolute;
            right: -32px;
            top: 18px;
            bottom: 18px;
            width: 16px;
            border-top: 2px solid #111827;
            border-bottom: 2px solid #111827;
            border-right: 2px solid #111827;
        }

        .bracket-match.has-next .match-connector::after {
            content: "";
            position: absolute;
            top: 50%;
            right: -18px;
            width: 18px;
            border-top: 2px solid #111827;
        }

        .slot-card {
            background: #e5e7eb;
            border-left: 8px solid #111827;
            color: #111827;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            min-height: 52px;
            display: flex;
            align-items: center;
            padding: 0.8rem 0.9rem;
            position: relative;
            transition: 0.2s ease;
        }

        .slot-card.empty {
            color: #9ca3af;
        }

        .slot-card.winner {
            border-left-color: #22C55E;
        }

        .slot-card.drag-over {
            outline: 2px dashed #00F0FF;
            transform: scale(1.02);
        }

        .team-chip {
            width: 100%;
            cursor: grab;
        }

        .team-chip.dragging {
            opacity: 0.45;
        }

        .team-pool.drag-over {
            box-shadow: inset 0 0 0 2px rgba(0, 240, 255, 0.45);
        }
    </style>
</head>
<body class="text-gray-200 font-sans min-h-screen">
    <main class="max-w-[1500px] mx-auto px-6 py-10">
        <nav class="flex flex-wrap items-center gap-8 mb-8">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">COMPETITION HUB</a>
            <a href="{{ route('competitor.tournaments.index') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">TOURNAMENTS</a>
            <a href="{{ route('competitor.profile') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">MON PROFIL</a>
            <a href="{{ route('organizer.dashboard') }}" class="text-gold font-display tracking-wider text-lg relative">
                MES TOURNOIS
                <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gold shadow-[0_0_10px_rgba(255,215,0,0.45)]"></span>
            </a>
            @if(auth()->user()->hasRole('Admin'))
                <a href="{{ route('admin.dashboard') }}" class="text-cyan hover:text-white font-display tracking-wider text-lg transition-colors">ADMINISTRATION</a>
            @endif
        </nav>

        <div class="flex flex-col xl:flex-row justify-between gap-6 mb-8">
            <div>
                @if(auth()->user()->hasRole('Admin'))
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-cyan transition">
        ← Retour à l'administration
    </a>
@else
    <a href="{{ route('organizer.dashboard') }}" class="text-sm text-gray-500 hover:text-gold transition">
        ← Retour à mes tournois
    </a>
@endif
                <h1 class="text-5xl font-display font-bold text-white tracking-wider mt-2">MATCHS & ARBRE</h1>
                <p class="text-gray-400">{{ $tournament->title }} • Construis le bracket, déplace les équipes, puis fais avancer les gagnants.</p>
            </div>

            <div class="grid grid-cols-2 gap-3 xl:min-w-[340px]">
                <div class="glass-card rounded-2xl p-4">
                    <div class="text-xs uppercase tracking-widest text-gray-500 mb-1">Équipes</div>
                    <div class="text-3xl font-display text-white">{{ $teams->count() }}</div>
                </div>
                <div class="glass-card rounded-2xl p-4">
                    <div class="text-xs uppercase tracking-widest text-gray-500 mb-1">Cases de bracket</div>
                    <div class="text-3xl font-display text-white">{{ $bracketRounds->flatten(1)->count() }}</div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-success font-bold">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl border border-crimson/30 bg-crimson/10 px-4 py-3 text-crimson font-bold">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-crimson/30 bg-crimson/10 px-4 py-3 text-crimson font-bold">{{ $errors->first() }}</div>
        @endif

        <div class="flex gap-3 mb-6">
            <button type="button" onclick="switchPanel('planning')" id="tab-planning" class="px-5 py-3 rounded-xl bg-gold text-black font-bold">Planification</button>
            <button type="button" onclick="switchPanel('bracket')" id="tab-bracket" class="px-5 py-3 rounded-xl bg-white/5 text-gray-300 border border-white/10 font-bold">Bracket manager</button>
        </div>

        <section id="panel-planning" class="space-y-6">
            <div class="glass-card rounded-3xl p-6 md:p-8">
                <h2 class="text-3xl font-display font-bold text-white mb-6">Créer un affrontement libre</h2>
                <form action="{{ route('organizer.matches.store', $tournament) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] gap-4 items-end">
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Équipe A</label>
                            <select name="team1_id" required class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
                                <option value="">Sélectionner une équipe...</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center text-3xl font-display text-crimson mb-3">VS</div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Équipe B</label>
                            <select name="team2_id" required class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
                                <option value="">Sélectionner une équipe...</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Date et heure</label>
                        <input type="datetime-local" name="played_at" class="w-full rounded-xl bg-black/40 border border-white/10 px-4 py-3 text-white">
                    </div>

                    <button type="submit" class="rounded-xl bg-gold hover:bg-yellow-500 text-black px-6 py-3 font-bold transition">
                        Programmer le match
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                @forelse($planningMatches as $match)
                    <article class="glass-card rounded-2xl p-5">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-cyan">Match #{{ $match->id }}</p>
                                <h3 class="text-2xl font-display font-bold text-white">
                                    {{ $match->team1->name ?? 'TBD' }}
                                    <span class="text-crimson">vs</span>
                                    {{ $match->team2->name ?? 'TBD' }}
                                </h3>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest {{ $match->status === 'Terminé' ? 'bg-success/10 text-success' : 'bg-white/5 text-gray-300' }}">
                                {{ $match->status }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 mb-4">
                            {{ optional($match->played_at)->format('d/m/Y H:i') ?? 'Horaire non défini' }}
                        </p>

                        @if($match->team1 && $match->team2 && $match->status !== 'Terminé')
                            <form action="{{ route('organizer.matches.update_score', ['tournament' => $tournament->id, 'match' => $match->id]) }}" method="POST" class="grid grid-cols-[1fr_auto_1fr_auto] gap-3 items-center">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="score_team1" min="0" required class="rounded-xl bg-black/40 border border-white/10 px-3 py-2 text-center text-white" placeholder="0">
                                <span class="text-gray-500 font-bold">-</span>
                                <input type="number" name="score_team2" min="0" required class="rounded-xl bg-black/40 border border-white/10 px-3 py-2 text-center text-white" placeholder="0">
                                <button type="submit" class="rounded-xl bg-success hover:bg-green-500 text-white px-4 py-2 font-bold transition">
                                    Valider
                                </button>
                            </form>
                        @elseif($match->score)
                            <div class="text-3xl font-display text-gold">{{ $match->score }}</div>
                        @endif
                    </article>
                @empty
                    <div class="glass-card rounded-2xl p-10 text-center text-gray-500 xl:col-span-2">
                        Aucun match n'a encore été programmé pour ce tournoi.
                    </div>
                @endforelse
            </div>
        </section>

        <section id="panel-bracket" class="hidden space-y-6">
            <div class="flex flex-col xl:flex-row gap-6">
                <aside class="glass-card rounded-3xl p-6 xl:w-[320px]">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <h2 class="text-2xl font-display font-bold text-white">Équipes libres</h2>
                        <form action="{{ route('organizer.matches.generate_bracket', $tournament) }}" method="POST" onsubmit="return confirm('Recréer l\'arbre ? Les matchs existants seront réinitialisés.');">
                            @csrf
                            <button type="submit" class="rounded-xl bg-gold hover:bg-yellow-500 text-black px-4 py-2 text-sm font-bold transition">
                                Générer
                            </button>
                        </form>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Glisse une équipe vers une case. Tu peux aussi la retirer en la ramenant ici.</p>
                    <div id="team-pool" class="team-pool rounded-2xl bg-black/30 border border-white/10 p-4 min-h-[220px] space-y-3">
                        @forelse($availableTeams as $team)
                            <div class="team-chip rounded-xl border border-white/10 bg-black/40 px-4 py-3 font-bold text-white" draggable="true" data-team-id="{{ $team->id }}">
                                {{ $team->name }}
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">Toutes les équipes sont déjà placées dans l'arbre.</div>
                        @endforelse
                    </div>
                </aside>

                <div class="flex-1">
                    @if($bracketRounds->isEmpty())
                        <div class="glass-card rounded-3xl p-10 text-center text-gray-400">
                            <h2 class="text-4xl font-display font-bold text-white mb-3">Aucun bracket généré</h2>
                            <p class="mb-6">Clique sur <strong>Générer</strong> pour créer automatiquement les rounds, puis réorganise librement l'arbre avec le drag & drop.</p>
                            <form action="{{ route('organizer.matches.generate_bracket', $tournament) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-xl bg-gold hover:bg-yellow-500 text-black px-6 py-3 font-bold transition">
                                    Générer l'arbre
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bracket-canvas" style="--round-one-matches: {{ max($roundOneMatches, 1) }};">
                            <div class="absolute top-10 right-10 text-right">
                                <h2 class="text-5xl font-display font-bold uppercase leading-none">Tournament<br>Bracket</h2>
                                <div class="w-3 h-20 bg-success ml-auto mt-3"></div>
                            </div>

                            <div class="bracket-board pt-20">
                                @php $lastRound = $bracketRounds->keys()->max(); @endphp
                                @foreach($bracketRounds as $roundNumber => $matches)
                                    <div class="round-column">
                                        <div class="round-label">Round {{ $roundNumber }}</div>

                                        @foreach($matches as $match)
                                            @php
                                                $team1Winner = $match->winner_team_id === $match->team1_id;
                                                $team2Winner = $match->winner_team_id === $match->team2_id;
                                                $hasTeams = $match->team1 || $match->team2;
                                            @endphp
                                            <div class="bracket-match {{ $roundNumber < $lastRound ? 'has-next' : '' }}">
                                                <div class="slot-card bracket-slot {{ $team1Winner ? 'winner' : '' }} {{ !$match->team1 ? 'empty' : '' }}" data-match-id="{{ $match->id }}" data-slot="team1_id">
                                                    @if($match->team1)
                                                        <div class="team-chip" draggable="true" data-team-id="{{ $match->team1->id }}" data-source-match="{{ $match->id }}" data-source-slot="team1_id">
                                                            {{ $match->team1->name }}
                                                        </div>
                                                    @else
                                                        Déposer l'équipe A
                                                    @endif
                                                </div>

                                                <div class="slot-card bracket-slot {{ $team2Winner ? 'winner' : '' }} {{ !$match->team2 ? 'empty' : '' }}" data-match-id="{{ $match->id }}" data-slot="team2_id">
                                                    @if($match->team2)
                                                        <div class="team-chip" draggable="true" data-team-id="{{ $match->team2->id }}" data-source-match="{{ $match->id }}" data-source-slot="team2_id">
                                                            {{ $match->team2->name }}
                                                        </div>
                                                    @else
                                                        Déposer l'équipe B
                                                    @endif
                                                </div>

                                                @if($roundNumber < $lastRound)
                                                    <div class="match-connector"></div>
                                                @endif

                                                @if($hasTeams)
                                                    <div class="grid {{ $match->team1 && $match->team2 ? 'grid-cols-2' : 'grid-cols-1' }} gap-2 mt-2">
                                                        @if($match->team1)
                                                            <button type="button" onclick="declareWinner({{ $match->id }}, {{ $match->team1->id }})" class="rounded-lg bg-white/80 hover:bg-success hover:text-white text-xs font-bold px-2 py-2 transition">
                                                                {{ $team1Winner ? 'Qualifié' : 'Gagnant A' }}
                                                            </button>
                                                        @endif
                                                        @if($match->team2)
                                                            <button type="button" onclick="declareWinner({{ $match->id }}, {{ $match->team2->id }})" class="rounded-lg bg-white/80 hover:bg-success hover:text-white text-xs font-bold px-2 py-2 transition">
                                                                {{ $team2Winner ? 'Qualifié' : 'Gagnant B' }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if($roundNumber === $lastRound && $match->winnerTeam)
                                                    <div class="mt-4 text-center">
                                                        <div class="slot-card winner justify-center">{{ $match->winnerTeam->name }}</div>
                                                        <div class="text-5xl mt-4">🏆</div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <script>
        let currentTournamentId = {{ $tournament->id }};
        let dragPayload = null;

        function switchPanel(panel) {
            const planning = document.getElementById('panel-planning');
            const bracket = document.getElementById('panel-bracket');
            const planningTab = document.getElementById('tab-planning');
            const bracketTab = document.getElementById('tab-bracket');

            if (panel === 'planning') {
                planning.classList.remove('hidden');
                bracket.classList.add('hidden');
                planningTab.className = 'px-5 py-3 rounded-xl bg-gold text-black font-bold';
                bracketTab.className = 'px-5 py-3 rounded-xl bg-white/5 text-gray-300 border border-white/10 font-bold';
            } else {
                planning.classList.add('hidden');
                bracket.classList.remove('hidden');
                bracketTab.className = 'px-5 py-3 rounded-xl bg-gold text-black font-bold';
                planningTab.className = 'px-5 py-3 rounded-xl bg-white/5 text-gray-300 border border-white/10 font-bold';
            }
        }

        function bindDragAndDrop() {
            document.querySelectorAll('.team-chip').forEach((chip) => {
                chip.addEventListener('dragstart', (event) => {
                    dragPayload = {
                        teamId: chip.dataset.teamId,
                        sourceMatch: chip.dataset.sourceMatch || null,
                        sourceSlot: chip.dataset.sourceSlot || null,
                    };
                    event.dataTransfer.setData('text/plain', chip.dataset.teamId);
                    chip.classList.add('dragging');
                });

                chip.addEventListener('dragend', () => {
                    chip.classList.remove('dragging');
                });
            });

            document.querySelectorAll('.bracket-slot').forEach((slot) => {
                slot.addEventListener('dragover', (event) => {
                    event.preventDefault();
                    slot.classList.add('drag-over');
                });

                slot.addEventListener('dragleave', () => {
                    slot.classList.remove('drag-over');
                });

                slot.addEventListener('drop', (event) => {
                    event.preventDefault();
                    slot.classList.remove('drag-over');

                    if (!dragPayload) {
                        return;
                    }

                    saveBracketPosition(slot.dataset.matchId, slot.dataset.slot, dragPayload.teamId);
                });
            });

            const teamPool = document.getElementById('team-pool');

            if (teamPool) {
                teamPool.addEventListener('dragover', (event) => {
                    event.preventDefault();
                    teamPool.classList.add('drag-over');
                });

                teamPool.addEventListener('dragleave', () => {
                    teamPool.classList.remove('drag-over');
                });

                teamPool.addEventListener('drop', (event) => {
                    event.preventDefault();
                    teamPool.classList.remove('drag-over');

                    if (!dragPayload || !dragPayload.sourceMatch || !dragPayload.sourceSlot) {
                        return;
                    }

                    saveBracketPosition(dragPayload.sourceMatch, dragPayload.sourceSlot, null);
                });
            }
        }

        function saveBracketPosition(matchId, slotKey, teamId) {
            fetch(`/organizer/tournaments/${currentTournamentId}/bracket`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    match_id: matchId,
                    slot: slotKey,
                    team_id: teamId
                })
            }).then(() => refreshBracket());
        }

        function declareWinner(matchId, teamId) {
            fetch(`/organizer/tournaments/${currentTournamentId}/bracket/winner`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    match_id: matchId,
                    winner_team_id: teamId
                })
            }).then(() => refreshBracket());
        }

        function refreshBracket() {
            window.location.reload();
        }

        bindDragAndDrop();
    </script>
</body>
</html>

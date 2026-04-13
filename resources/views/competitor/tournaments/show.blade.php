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
                    fontFamily: { display: ['Teko', 'sans-serif'], sans: ['Outfit', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        body { background-color: #050505; }
        .glass-card {
            background: rgba(15, 20, 30, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* --- STYLE DU BRACKET (Le même que pour l'organisateur) --- */
        .clean-bracket-wrapper {
            background-color: #f8f9fa; padding: 4rem 3rem; border-radius: 16px; color: #111827;
            font-family: 'Outfit', sans-serif; overflow-x: auto; position: relative; border: 1px solid #e5e7eb;
        }
        .bracket-header { position: absolute; top: 2rem; right: 3rem; text-align: right; display: flex; align-items: center; gap: 1rem; }
        .bracket-header h2 { font-size: 2rem; line-height: 1; font-weight: 900; text-transform: uppercase; margin: 0; }
        .header-accent { width: 12px; height: 60px; background-color: #22c55e; }
        .clean-team-node {
            background-color: #e5e7eb; color: #111827; font-weight: 600; font-size: 0.85rem; padding: 12px 16px;
            width: 170px; border-left: 8px solid #1f2937; text-transform: uppercase; letter-spacing: 0.5px; position: relative; z-index: 10;
        }
        .clean-team-empty { color: #9ca3af; }
        .clean-match { display: flex; flex-direction: column; justify-content: space-between; position: relative; }
        .clean-connector {
            position: absolute; right: -30px; top: 20px; bottom: 20px; width: 15px;
            border-top: 2px solid #1f2937; border-bottom: 2px solid #1f2937; border-right: 2px solid #1f2937;
        }
        .clean-connector::after { content: ''; position: absolute; top: 50%; right: -15px; width: 15px; border-top: 2px solid #1f2937; }
        .clean-green-accent { position: absolute; top: 50%; right: -4px; transform: translateY(-50%); width: 8px; height: 24px; background-color: #22c55e; z-index: 5; }
        .clean-round { display: flex; flex-direction: column; justify-content: space-around; margin-right: 45px; }
        
        /* Highlight pour l'équipe du joueur connecté */
        .my-team-node { border-left-color: #DC143C !important; background-color: #fee2e2 !important; color: #991b1b !important; }
    </style>
</head>

<body class="text-gray-200 font-sans min-h-screen flex flex-col">

    <nav class="fixed top-0 w-full z-50 glass border-b border-white/5 h-20 flex items-center px-6 justify-between">
        <a href="{{ route('dashboard') }}" class="text-2xl font-display font-bold text-white tracking-widest hover:text-crimson transition">YOUCODE ARENA</a>
        <a href="{{ route('competitor.tournaments.index') }}" class="text-gray-400 hover:text-white font-bold text-sm">⬅️ RETOUR AUX TOURNOIS</a>
    </nav>

    <main class="flex-grow pt-20">
        
        <div class="relative w-full h-80 flex items-end pb-8">
            <div class="absolute inset-0 z-0">
                <img src="{{ $tournament->image_path ? asset('storage/' . $tournament->image_path) : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070' }}" class="w-full h-full object-cover opacity-40">
                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/80 to-transparent"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 w-full flex justify-between items-end">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-gold text-black text-xs px-3 py-1 rounded-full font-bold uppercase tracking-widest">{{ $tournament->game->name ?? 'Jeu' }}</span>
                        <span class="border border-white/20 text-gray-300 text-xs px-3 py-1 rounded-full uppercase tracking-widest">{{ $tournament->status }}</span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-display font-bold text-white uppercase tracking-wider">{{ $tournament->title }}</h1>
                    <p class="text-gray-400 mt-2 flex gap-6 text-sm">
                        <span>📅 {{ \Carbon\Carbon::parse($tournament->event_date)->format('d M Y - H:i') }}</span>
                        <span>📍 {{ $tournament->location ?? 'En ligne' }}</span>
                        <span>👥 {{ $tournament->teams_count ?? 0 }} / {{ $tournament->max_capacity }} Places</span>
                    </p>
                </div>
                
                <div>
                    @if($isRegistered)
                        <div class="bg-success/20 border border-success/50 text-success px-6 py-3 rounded text-center font-display tracking-widest text-lg shadow-[0_0_15px_rgba(34,197,94,0.3)]">
                            ✅ TU ES INSCRIT
                        </div>
                    @elseif($tournament->status == 'À venir')
                        <a href="{{ route('competitor.teams.create', $tournament->id) }}" class="bg-crimson hover:bg-red-700 text-white px-8 py-3 rounded font-display tracking-widest text-lg transition shadow-neon">
                            S'INSCRIRE AU TOURNOI
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-8">
            
            <div class="flex border-b border-white/10 mb-8 gap-8">
                <button onclick="switchTab('overview')" id="tab-overview" class="pb-3 text-lg font-display tracking-wider text-crimson border-b-2 border-crimson transition">APERÇU</button>
                <button onclick="switchTab('participants')" id="tab-participants" class="pb-3 text-lg font-display tracking-wider text-gray-500 border-b-2 border-transparent hover:text-white transition">PARTICIPANTS</button>
                <button onclick="switchTab('bracket')" id="tab-bracket" class="pb-3 text-lg font-display tracking-wider text-gray-500 border-b-2 border-transparent hover:text-white transition">ARBRE DU TOURNOI</button>
            </div>

            <div id="content-overview" class="grid grid-cols-1 md:grid-cols-3 gap-8 block">
                <div class="md:col-span-2 glass-card p-8 rounded-xl">
                    <h2 class="text-2xl font-display font-bold text-white mb-4 border-b border-white/10 pb-2">À propos du tournoi</h2>
                    <p class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $tournament->description ?? 'Aucune description fournie par l\'organisateur.' }}</p>
                </div>
                <div class="space-y-6">
                    <div class="glass-card p-6 rounded-xl border-t-4 border-t-gold">
                        <h3 class="font-display font-bold text-xl text-white mb-2">Récompenses 🏆</h3>
                        <p class="text-gray-400 text-sm">1ère place : <span class="text-gold font-bold">1000 MAD</span></p>
                        <p class="text-gray-400 text-sm">2ème place : 500 MAD</p>
                    </div>
                    <div class="glass-card p-6 rounded-xl border-t-4 border-t-cyan">
                        <h3 class="font-display font-bold text-xl text-white mb-2">Organisateur 👑</h3>
                        <p class="text-gray-400 text-sm flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-gray-700 block"></span>
                            {{ $tournament->organizer->username ?? 'YouCode Admin' }}
                        </p>
                    </div>
                </div>
            </div>

            <div id="content-participants" class="hidden">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @forelse($tournament->teams ?? [] as $team)
                        <div class="bg-black/40 border border-white/5 rounded-lg p-4 text-center group hover:border-cyan/50 transition">
                            <div class="w-16 h-16 bg-gray-800 rounded-full mx-auto mb-3 flex items-center justify-center text-2xl group-hover:scale-110 transition">👾</div>
                            <h4 class="font-bold text-white truncate">{{ $team->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $team->members->count() }} Joueur(s)</p>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-12">
                            <p class="text-xl">Personne n'a encore rejoint l'arène.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div id="content-bracket" class="hidden">
                <div class="clean-bracket-wrapper">
                    <div class="bracket-header">
                        <h2>Tournament<br>Bracket</h2>
                        <div class="header-accent"></div>
                    </div>
                    
                    <div class="flex mt-16">
                        <div class="clean-round gap-4">
                            <div class="clean-match h-[110px]">
                                <div class="clean-team-node my-team-node">OUSSAMA_PRO (Toi)</div>
                                <div class="clean-team-node">PLAYER_02</div>
                                <div class="clean-connector"><div class="clean-green-accent"></div></div>
                            </div>
                            <div class="clean-match h-[110px]">
                                <div class="clean-team-node">TEAM ALPHA</div>
                                <div class="clean-team-node clean-team-empty">TBD</div>
                                <div class="clean-connector"><div class="clean-green-accent"></div></div>
                            </div>
                            </div>
                    </div>
                </div>
                
                @if($isRegistered)
                    <div class="mt-6 bg-blue-500/10 border border-blue-500/30 text-blue-400 p-4 rounded-lg flex items-center gap-3">
                        ℹ️ <strong>Information :</strong> Ton équipe est mise en évidence en rouge dans l'arbre du tournoi. Surveille tes prochains matchs !
                    </div>
                @endif
            </div>

        </div>
    </main>

    <script>
        function switchTab(tabId) {
            // Cacher tous les contenus
            ['overview', 'participants', 'bracket'].forEach(id => {
                document.getElementById('content-' + id).classList.add('hidden');
                document.getElementById('content-' + id).classList.remove('block');
                
                let tab = document.getElementById('tab-' + id);
                tab.classList.remove('text-crimson', 'border-crimson');
                tab.classList.add('text-gray-500', 'border-transparent');
            });

            // Afficher le contenu ciblé
            document.getElementById('content-' + tabId).classList.remove('hidden');
            document.getElementById('content-' + tabId).classList.add('block');
            
            // Styliser l'onglet actif
            let activeTab = document.getElementById('tab-' + tabId);
            activeTab.classList.remove('text-gray-500', 'border-transparent');
            activeTab.classList.add('text-crimson', 'border-crimson');
        }
    </script>
</body>
</html>
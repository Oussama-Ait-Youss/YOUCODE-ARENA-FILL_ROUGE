<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Organisateur - YouCode Arena</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#050505',
                        'bg-soft': '#0B0F19',
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
            background-image: radial-gradient(circle at 50% 10%, rgba(255, 215, 0, 0.05) 0%, transparent 40%);
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #FFD700; }

        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.8), rgba(10, 12, 18, 0.9));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 215, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* Animations de changement de vue */
        .fade-in { animation: fadeIn 0.3s ease-in-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* --- NOUVEAU STYLE : ARBRE DE TOURNOI (STYLE CLAIR & MODERNE) --- */
        .clean-bracket-wrapper {
            background-color: #f8f9fa; /* Fond gris très clair */
            padding: 4rem 3rem;
            border-radius: 16px;
            color: #111827;
            font-family: 'Outfit', sans-serif;
            overflow-x: auto;
            position: relative;
            border: 1px solid #e5e7eb;
        }

        /* Le Titre en haut à droite */
        .bracket-header {
            position: absolute;
            top: 2rem;
            right: 3rem;
            text-align: right;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .bracket-header h2 {
            font-size: 2rem;
            line-height: 1;
            font-weight: 900;
            text-transform: uppercase;
            color: #111827;
            margin: 0;
        }
        .header-accent {
            width: 12px;
            height: 60px;
            background-color: #22c55e; /* Vert de l'image */
        }

        /* Design des équipes (Boîte grise rectangulaire) */
        .clean-team-node {
            background-color: #e5e7eb; 
            color: #111827;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 12px 16px;
            width: 170px;
            border-left: 8px solid #1f2937; /* Bordure noire à gauche */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 10;
        }
        .clean-team-empty {
            color: #9ca3af;
        }

        /* Conteneur d'un Match (2 équipes) */
        .clean-match {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        /* Lignes de connexion en forme de "]" */
        .clean-connector {
            position: absolute;
            right: -30px;
            top: 20px; /* Centre de l'équipe du haut */
            bottom: 20px; /* Centre de l'équipe du bas */
            width: 15px;
            border-top: 2px solid #1f2937;
            border-bottom: 2px solid #1f2937;
            border-right: 2px solid #1f2937;
        }

        /* Petite barre horizontale sortant du "]" vers le match suivant */
        .clean-connector::after {
            content: ''; position: absolute; top: 50%; right: -15px; width: 15px; border-top: 2px solid #1f2937;
        }

        /* Le petit rectangle vert d'accentuation sur les lignes */
        .clean-green-accent {
            position: absolute; top: 50%; right: -4px; transform: translateY(-50%);
            width: 8px; height: 24px; background-color: #22c55e; z-index: 5;
        }

        /* Colonnes de l'arbre */
        .clean-round {
            display: flex; flex-direction: column; justify-content: space-around; margin-right: 45px;
        }

        /* Fullscreen styles */
        #bracket-container:fullscreen {
            background-color: #f8f9fa; /* Consistent background in fullscreen */
            padding: 2rem;
            overflow: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #bracket-container:fullscreen .clean-bracket-wrapper {
            border: none;
            box-shadow: none;
            transform: scale(1.4); /* Scaled up for LAN event projection */
            transform-origin: center center;
        }
    </style>
</head>

<body class="text-gray-200 font-sans min-h-screen relative selection:bg-gold selection:text-black flex flex-col">

    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>

    <nav class="fixed top-0 w-full z-50 bg-[#050505]/90 backdrop-blur-md border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-crimson flex items-center justify-center transform skew-x-[-10deg]">
                    <span class="font-display font-bold text-black text-2xl transform skew-x-[10deg]">Y</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-display font-bold tracking-widest leading-none text-white group-hover:text-crimson transition-colors">YOUCODE</span>
                    <span class="text-xs font-display tracking-[0.3em] text-gray-500 group-hover:text-white transition-colors">ARENA</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">COMPETITION HUB</a>
                <a href="{{ route('competitor.tournaments.index') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">TOURNAMENTS</a>
                <a href="{{ route('competitor.profile') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">MON PROFIL</a>
                
                <a href="{{ route('organizer.dashboard') }}" class="text-gold font-display tracking-wider text-lg relative flex items-center gap-1">
                     MES TOURNOIS
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gold shadow-[0_0_10px_rgba(255,215,0,0.5)]"></span>
                </a>

                @if(auth()->user()->hasRole('Admin'))
                    <a href="{{ route('admin.dashboard') }}" class="text-cyan hover:text-white font-display tracking-wider text-lg transition-colors flex items-center gap-1"> ADMINISTRATION</a>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 cursor-pointer group" onclick="document.getElementById('logout-form').submit();" title="Se déconnecter">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-gold to-orange-500 border-2 border-white/20 relative group-hover:scale-105 transition">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=transparent&color=fff" class="w-full h-full object-cover rounded-full">
                    </div>
                    <span class="text-gray-400 group-hover:text-gold font-bold text-sm tracking-widest uppercase transition-colors hidden sm:block">Déconnexion</span>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </nav>

    <main class="relative z-10 flex-grow max-w-7xl mx-auto w-full p-6 pt-28">

        <div id="view-dashboard" class="block fade-in">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-4xl font-display font-bold text-white tracking-wider uppercase mb-1 flex items-center gap-3">
                        <span class="text-gold">👑</span> Espace Organisateur
                    </h1>
                    <p class="text-gray-400 text-sm">Gère tes compétitions, valide les équipes et surveille l'avancée de l'arbre.</p>
                </div>
                @if($tournaments->isEmpty())
                    <a href="{{ route('organizer.tournaments.create') }}" class="bg-gold hover:bg-yellow-500 text-black px-6 py-2 rounded font-bold transition shadow-[0_0_15px_rgba(255,215,0,0.3)] inline-block">
                        + Nouveau Tournoi
                    </a>
                @else
                    <div class="bg-white/5 border border-white/10 text-gray-500 px-6 py-2 rounded font-bold cursor-not-allowed inline-block" title="Limite atteinte">
                        🔒 Capacité atteinte (1/1)
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="glass-card p-6 border-t-4 border-t-gold">
                    <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-1">Mes Tournois Actifs</div>
                    <div class="text-4xl font-display font-bold text-white">2</div>
                </div>
                <div class="glass-card p-6 border-t-4 border-t-warning">
                    <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-1">Demandes en attente</div>
                    <div class="text-4xl font-display font-bold text-warning">5</div>
                </div>
                <div class="glass-card p-6 border-t-4 border-t-cyan">
                    <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-1">Matchs à arbitrer</div>
                    <div class="text-4xl font-display font-bold text-cyan">1</div>
                </div>
            </div>

            <h2 class="text-2xl font-display font-bold text-white mb-4 border-b border-white/10 pb-2">Mes Compétitions</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($tournaments as $tournament)
                        <div class="glass-card p-6 rounded-xl relative overflow-hidden group flex flex-col justify-between">

            <div class="absolute top-4 right-4 bg-white/10 text-white text-xs px-2 py-1 rounded font-bold uppercase tracking-widest border border-white/10">
                {{ $tournament->status }}
            </div>

            <div>
                <h3 class="text-2xl font-display font-bold text-white mb-1 pr-16 truncate" title="{{ $tournament->title }}">
                    {{ $tournament->title }}
                </h3>
                <p class="text-sm text-cyan mb-4 flex items-center gap-2 font-bold tracking-wider">
                    🎮 {{ $tournament->game->name ?? 'Jeu' }}
                </p>
                
                <div class="flex justify-between text-sm border-t border-white/10 py-3 mt-2">
                    <span class="text-gray-500">Inscrits: 
                        <span class="{{ ($tournament->registered_count ?? 0) >= $tournament->max_capacity ? 'text-crimson' : 'text-success' }} font-bold">
                            {{ $tournament->registered_count ?? 0 }}/{{ $tournament->max_capacity }}
                        </span>
                    </span>
                    <span class="text-gray-400 font-bold flex items-center gap-1">
                        📅 {{ \Carbon\Carbon::parse($tournament->event_date)->format('d M') }}
                    </span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-white/5">
                
                <button onclick="openTournamentView('{{ addslashes($tournament->title) }}', '{{ $tournament->id }}')" class="w-full bg-white/5 hover:bg-gold hover:text-black border border-white/10 text-gold font-bold py-2 rounded transition mb-3">
                    Panneau de Contrôle 
                </button>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('organizer.tournaments.edit', $tournament->id) }}" class="text-center bg-cyan/10 border border-cyan/20 hover:bg-cyan hover:text-black text-cyan text-sm font-bold py-2 rounded transition">
                         Modifier
                    </a>
                    
                    <form action="{{ route('organizer.tournaments.destroy', $tournament->id) }}" method="POST" class="m-0" onsubmit="return confirm('Es-tu sûr de vouloir supprimer définitivement ce tournoi ? Tous les matchs seront perdus.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-center bg-crimson/10 border border-crimson/20 hover:bg-crimson hover:text-white text-crimson text-sm font-bold py-2 rounded transition">
                             Supprimer
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <div class="col-span-full glass-card p-12 rounded-xl text-center border border-dashed border-white/20">
            <div class="text-4xl mb-4"></div>
            <h3 class="text-xl font-display font-bold text-white mb-2">Aucune compétition active</h3>
            <p class="text-gray-500 mb-6">Tu n'as pas encore créé de tournoi. C'est le moment de lancer l'arène !</p>
            <a href="{{ route('organizer.tournaments.create') }}" class="inline-block bg-gold hover:bg-yellow-500 text-black px-6 py-2 rounded font-bold transition shadow-[0_0_15px_rgba(255,215,0,0.3)]">
                + Nouveau Tournoi
            </a>
        </div>
    @endforelse
</div>

            </div>
        </div>

        <div id="view-tournament" class="hidden fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 bg-black/40 p-6 rounded-xl border border-white/5">
                <div>
                    <button onclick="closeTournamentView()" class="text-gray-500 hover:text-white text-sm font-bold flex items-center gap-2 mb-2 transition">
                        ⬅️ Retour à mes tournois
                    </button>
                    <h1 id="panel-title" class="text-3xl font-display font-bold text-gold tracking-wider uppercase">Nom du Tournoi</h1>
                </div>
                <div class="flex gap-2">
                    <span class="bg-white/10 text-white px-3 py-1 rounded text-sm font-bold">Statut: Ouvert</span>
                    <button class="bg-crimson/20 border border-crimson/50 text-crimson px-3 py-1 rounded text-sm font-bold hover:bg-crimson hover:text-white transition">Clôturer</button>
                </div>
            </div>

            <div class="flex border-b border-white/10 mb-6 gap-6">
                <button onclick="switchTab('participants')" id="tab-participants" class="pb-3 font-bold text-cyan border-b-2 border-cyan px-2 transition"> Participants</button>
                <button onclick="switchTab('bracket')" id="tab-bracket" class="pb-3 font-bold text-gray-500 border-b-2 border-transparent hover:text-white px-2 transition"> Arbre (Map)</button>
            </div>

            <div id="tab-content-participants" class="block fade-in">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-black/40 border border-white/5 rounded-lg p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-cyan/20 rounded-full flex items-center justify-center text-cyan font-bold">1</div>
                        <div>
                            <div class="font-bold text-white">Oussama_Pro</div>
                            <div class="text-xs text-gray-500">Validé</div>
                        </div>
                    </div>
                    <div class="bg-black/40 border border-white/5 rounded-lg p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-cyan/20 rounded-full flex items-center justify-center text-cyan font-bold">2</div>
                        <div>
                            <div class="font-bold text-white">Ghost_Rider</div>
                            <div class="text-xs text-gray-500">Validé</div>
                        </div>
                    </div>
                    <div class="bg-black/40 border border-white/5 rounded-lg p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-cyan/20 rounded-full flex items-center justify-center text-cyan font-bold">3</div>
                        <div>
                            <div class="font-bold text-white">Team Rocket</div>
                            <div class="text-xs text-gray-500">Validé</div>
                        </div>
                    </div>
                    <div class="bg-black/40 border border-white/5 rounded-lg p-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-cyan/20 rounded-full flex items-center justify-center text-cyan font-bold">4</div>
                        <div>
                            <div class="font-bold text-white">FNC Masters</div>
                            <div class="text-xs text-gray-500">Validé</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-content-bracket" class="hidden fade-in">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Sidebar pour les participants (Drag Source) -->
                    <div class="w-full lg:w-64 bg-black/40 border border-white/5 p-4 rounded-xl">
                        <h3 class="text-gold font-bold mb-4 border-b border-white/10 pb-2">Joueurs Acceptés</h3>
                        <div id="bracket-participants-list" class="flex flex-col gap-2 min-h-[200px]" ondragover="allowDrop(event)" ondrop="handleDrop(event, null, null)">
                            <!-- Draggable Team items -->
                            <div class="clean-team-node cursor-grab active:cursor-grabbing bg-gray-700 w-full text-white" draggable="true" id="team-1" data-team-id="1" ondragstart="handleDragStart(event)">OUSSAMA_PRO</div>
                            <div class="clean-team-node cursor-grab active:cursor-grabbing bg-gray-700 w-full text-white" draggable="true" id="team-2" data-team-id="2" ondragstart="handleDragStart(event)">GHOST_RIDER</div>
                            <div class="clean-team-node cursor-grab active:cursor-grabbing bg-gray-700 w-full text-white" draggable="true" id="team-3" data-team-id="3" ondragstart="handleDragStart(event)">TEAM ALPHA</div>
                            <div class="clean-team-node cursor-grab active:cursor-grabbing bg-gray-700 w-full text-white" draggable="true" id="team-4" data-team-id="4" ondragstart="handleDragStart(event)">FNC MASTERS</div>
                        </div>
                    </div>

                    <!-- L'Arbre Drag and Drop -->
                    <div id="bracket-container" class="flex-grow bg-[#f8f9fa] rounded-xl relative overflow-auto">
                        <button onclick="toggleFullScreen()" class="absolute top-4 left-4 z-50 bg-black/80 hover:bg-black text-white px-4 py-2 rounded font-bold shadow transition border border-white/20">
                            🔲 Afficher en Plein Écran
                        </button>
                        <div class="clean-bracket-wrapper border-none">
                        <div class="bracket-header">
                            <h2>Tournament<br>Bracket</h2>
                            <div class="header-accent"></div>
                        </div>

                        <div class="flex mt-16 overflow-x-auto pb-4">
                            <!-- Round 1 -->
                            <div class="clean-round gap-4">
                                <div class="clean-match h-[110px]" data-match-id="1">
                                    <div class="clean-team-node dropzone bg-gray-200" data-slot="team1_id" ondragover="allowDrop(event)" ondrop="handleDrop(event, 1, 'team1_id')">TBD</div>
                                    <div class="clean-team-node dropzone bg-gray-200" data-slot="team2_id" ondragover="allowDrop(event)" ondrop="handleDrop(event, 1, 'team2_id')">TBD</div>
                                    <div class="clean-connector"><div class="clean-green-accent"></div></div>
                                </div>
                                <div class="clean-match h-[110px]" data-match-id="2">
                                    <div class="clean-team-node dropzone bg-gray-200" data-slot="team1_id" ondragover="allowDrop(event)" ondrop="handleDrop(event, 2, 'team1_id')">TBD</div>
                                    <div class="clean-team-node dropzone bg-gray-200" data-slot="team2_id" ondragover="allowDrop(event)" ondrop="handleDrop(event, 2, 'team2_id')">TBD</div>
                                    <div class="clean-connector"><div class="clean-green-accent"></div></div>
                                </div>
                            </div>

                            <!-- Round 2 -->
                            <div class="clean-round gap-4">
                                <div class="clean-match h-[236px]" data-match-id="3">
                                    <!-- Slots automatiques de progression, ou drag drop manuel ? Normalement c'est manuel si le score n'est pas fait -->
                                    <div class="clean-team-node bg-gray-300 opacity-50" title="Vainqueur Match 1">TBD</div>
                                    <div class="clean-team-node bg-gray-300 opacity-50" title="Vainqueur Match 2">TBD</div>
                                    <div class="clean-connector"><div class="clean-green-accent"></div></div>
                                </div>
                            </div>

                            <div class="clean-round justify-center">
                                <div class="flex flex-col items-center gap-6">
                                    <div class="clean-team-node text-center w-48 bg-gold text-black border-l-black">WINNER</div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>

    </main>

    <div id="toast" class="fixed bottom-5 right-5 bg-success/20 border border-success/50 text-success px-4 py-3 rounded-lg font-bold shadow-lg transition-transform transform translate-x-full z-50">
        Action effectuée avec succès !
    </div>

    <script>

        function closeTournamentView() {
            document.getElementById('view-tournament').classList.add('hidden');
            document.getElementById('view-dashboard').classList.remove('hidden');
        }

        function switchTab(tabName) {
            ['participants', 'bracket'].forEach(t => {
                document.getElementById('tab-content-' + t).classList.add('hidden');
                document.getElementById('tab-' + t).className = "pb-3 font-bold text-gray-500 border-b-2 border-transparent hover:text-white px-2 transition";
            });
            
            document.getElementById('tab-content-' + tabName).classList.remove('hidden');
            
            let colorClass = tabName === 'participants' ? 'text-cyan border-cyan' : 'text-gold border-gold';
            document.getElementById('tab-' + tabName).className = `pb-3 font-bold ${colorClass} border-b-2 px-2 transition`;
        }

        function acceptRow(btn) {
            btn.closest('tr').remove();
            showToast('Candidature acceptée ! Le joueur a été ajouté.');
        }

        function rejectRow(btn) {
            btn.closest('tr').remove();
            showToast('Candidature refusée.', 'crimson');
        }

        function showToast(msg, color = 'success') {
            let toast = document.getElementById('toast');
            toast.innerText = msg;
            toast.className = `fixed bottom-5 right-5 bg-${color}/20 border border-${color}/50 text-${color} px-4 py-3 rounded-lg font-bold shadow-lg transition-transform z-50`;
            toast.style.transform = 'translateX(0)';
            
            setTimeout(() => {
                toast.style.transform = 'translateX(200%)';
            }, 3000);
        }

        let currentTournamentId = null;

        function openTournamentView(title, tournamentId) {
            currentTournamentId = tournamentId; 
            document.getElementById('view-dashboard').classList.add('hidden');
            document.getElementById('view-tournament').classList.remove('hidden');
            document.getElementById('panel-title').innerText = title;
            switchTab('participants'); 

            fetch(`/organizer/tournaments/${tournamentId}/data`)
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        const list = document.getElementById('bracket-participants-list');
                        list.innerHTML = '';
                        
                        data.teams.forEach(team => {
                       
                            
                            const div = document.createElement('div');
                            div.className = 'clean-team-node cursor-grab active:cursor-grabbing bg-gray-700 w-full text-white team-drag';
                            div.setAttribute('draggable', 'true');
                            div.id = 'team-' + team.id;
                            div.setAttribute('data-team-id', team.id);
                            div.ondragstart = handleDragStart;
                            div.innerText = team.name;

                            let isPlaced = false;
                            data.matches.forEach(m => {
                                if(m.team1_id === team.id || m.team2_id === team.id) isPlaced = true;
                            });

                            if(!isPlaced) {
                                list.appendChild(div);
                            }

                        });

                        document.querySelectorAll('.clean-match[data-match-id]').forEach(matchEl => {
                            const matchIdStr = matchEl.getAttribute('data-match-id');
                            const matchData = data.matches.find(m => String(m.id) === matchIdStr);
                            
                            const slot1 = matchEl.querySelector('[data-slot="team1_id"]');
                            const slot2 = matchEl.querySelector('[data-slot="team2_id"]');
                            
                            if (slot1 && slot2) {
                                slot1.innerText = 'TBD';
                                slot1.className = 'clean-team-node dropzone bg-gray-200';
                                slot2.innerText = 'TBD';
                                slot2.className = 'clean-team-node dropzone bg-gray-200';

                                if (matchData) {
                                    if(matchData.team1_id) injectTeamToSlot(slot1, data.teams.find(t=>t.id===matchData.team1_id));
                                    if(matchData.team2_id) injectTeamToSlot(slot2, data.teams.find(t=>t.id===matchData.team2_id));

                                    if(matchData.team1_id && matchData.team2_id && !matchData.winner_team_id) {
                                        addWinnerButton(slot1, matchData.id, matchData.team1_id);
                                        addWinnerButton(slot2, matchData.id, matchData.team2_id);
                                    } else if (matchData.winner_team_id) {
                                        if (matchData.team1_id === matchData.winner_team_id) slot1.classList.replace('bg-gold', 'bg-success');
                                        if (matchData.team2_id === matchData.winner_team_id) slot2.classList.replace('bg-gold', 'bg-success');
                                    }
                                }
                            }
                        });
                    }
                });
        }

        function injectTeamToSlot(slot, team) {
            if(!team) return;
            const div = document.createElement('div');
            div.className = 'clean-team-node cursor-grab active:cursor-grabbing bg-gold w-full text-black team-drag';
            div.setAttribute('draggable', 'true');
            div.id = 'team-' + team.id;
            div.setAttribute('data-team-id', team.id);
            div.ondragstart = handleDragStart;
            div.innerText = team.name;

            slot.innerText = '';
            slot.appendChild(div);
        }

        function addWinnerButton(slot, matchId, teamId) {
            const btn = document.createElement('button');
            btn.className = "absolute right-[-25px] top-1 bg-success text-white text-xs w-6 h-6 rounded-full flex items-center justify-center font-bold shadow hover:scale-110 transition z-50";
            btn.innerHTML = "W";
            btn.title = "Déclarer Gagnant";
            btn.onclick = function(e) {
                e.stopPropagation();
                declareWinner(matchId, teamId);
            };
            slot.style.position = 'relative';
            slot.appendChild(btn);
        }

        function declareWinner(matchId, teamId) {
            fetch(`/organizer/tournaments/${currentTournamentId}/bracket/winner`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ match_id: matchId, winner_team_id: teamId })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    showToast('Gagnant déclaré !', 'success');
                    openTournamentView(document.getElementById('panel-title').innerText, currentTournamentId);
                } else {
                    showToast(data.message, 'crimson');
                }
            });
        }

        function toggleFullScreen() {
            const container = document.getElementById("bracket-container");
            if (!document.fullscreenElement) {
                container.requestFullscreen().catch((err) => {
                    showToast("Erreur lors du passage en plein écran.", "crimson");
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function handleDragStart(event) {
            event.dataTransfer.setData("text/plain", event.target.id);
            event.target.classList.add('opacity-50');
        }

        function allowDrop(event) {
            event.preventDefault();
        }

        function handleDrop(event, matchId, slotKey) {
            event.preventDefault();
            const id = event.dataTransfer.getData("text");
            const draggableElement = document.getElementById(id);
            draggableElement.classList.remove('opacity-50');

            const dropzone = event.target.closest('.dropzone') || event.target.closest('#bracket-participants-list');
            
            if (!dropzone) return;

            if (dropzone.contains(draggableElement)) return;

            if (dropzone.classList.contains('dropzone')) {
                if(dropzone.innerText === "TBD") dropzone.innerText = "";
                
                dropzone.appendChild(draggableElement);
                draggableElement.classList.replace('bg-gray-700', 'bg-gold');
                draggableElement.classList.replace('text-white', 'text-black');
                
                const teamId = draggableElement.getAttribute('data-team-id');
                saveBracketPosition(matchId, slotKey, teamId);
            } 
            else if (dropzone.id === 'bracket-participants-list') {
                dropzone.appendChild(draggableElement);
                draggableElement.classList.replace('bg-gold', 'bg-gray-700');
                draggableElement.classList.replace('text-black', 'text-white');
            }
        }

        document.addEventListener('dragend', function(event) {
            if(event.target.classList && event.target.classList.contains('team-drag')) {
                event.target.classList.remove('opacity-50');
            }
        });

        function saveBracketPosition(matchId, slotKey, teamId) {
            if (!currentTournamentId) return;

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
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showToast('Position sauvegardée dans la DB !');
                } else {
                    showToast('Erreur lors de la sauvegarde.', 'crimson');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Erreur serveur de sauvegarde.', 'crimson');
            });
        }
    </script>
</body>
</html>
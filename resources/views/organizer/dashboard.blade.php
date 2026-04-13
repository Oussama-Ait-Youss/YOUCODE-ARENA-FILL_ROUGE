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
                    👑 MES TOURNOIS
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-gold shadow-[0_0_10px_rgba(255,215,0,0.5)]"></span>
                </a>

                @if(auth()->user()->hasRole('Admin'))
                    <a href="{{ route('admin.dashboard') }}" class="text-cyan hover:text-white font-display tracking-wider text-lg transition-colors flex items-center gap-1">🛡️ ADMINISTRATION</a>
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
                <button class="bg-gold hover:bg-yellow-500 text-black px-6 py-2 rounded font-bold transition shadow-[0_0_15px_rgba(255,215,0,0.3)]">
                    + Nouveau Tournoi
                </button>
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
                
                <div class="glass-card p-6 rounded-xl relative overflow-hidden group">
                    <div class="absolute top-4 right-4 bg-success/20 text-success text-xs px-2 py-1 rounded font-bold uppercase tracking-widest">En cours</div>
                    <h3 class="text-2xl font-display font-bold text-white mb-1">Winter Cup FIFA</h3>
                    <p class="text-sm text-gray-400 mb-4 flex items-center gap-2">🎮 FIFA 26</p>
                    
                    <div class="flex justify-between text-sm border-t border-white/10 py-3 mt-2">
                        <span class="text-gray-500">Inscrits: <span class="text-white font-bold">16/16</span></span>
                        <span class="text-warning font-bold flex items-center gap-1">⏱️ 2 En attente</span>
                    </div>
                    
                    <button onclick="openTournamentView('Winter Cup FIFA', 'fifa')" class="w-full bg-white/5 hover:bg-gold hover:text-black border border-white/10 text-gold font-bold py-2 rounded transition mt-2">
                        Panneau de Contrôle ⚙️
                    </button>
                </div>

                <div class="glass-card p-6 rounded-xl relative overflow-hidden group">
                    <div class="absolute top-4 right-4 bg-cyan/20 text-cyan text-xs px-2 py-1 rounded font-bold uppercase tracking-widest">À Venir</div>
                    <h3 class="text-2xl font-display font-bold text-white mb-1">League of Legends S2</h3>
                    <p class="text-sm text-gray-400 mb-4 flex items-center gap-2">🎮 LoL</p>
                    
                    <div class="flex justify-between text-sm border-t border-white/10 py-3 mt-2">
                        <span class="text-gray-500">Inscrits: <span class="text-white font-bold">4/8</span></span>
                        <span class="text-warning font-bold flex items-center gap-1">⏱️ 3 En attente</span>
                    </div>
                    
                    <button onclick="openTournamentView('League of Legends S2', 'lol')" class="w-full bg-white/5 hover:bg-gold hover:text-black border border-white/10 text-gold font-bold py-2 rounded transition mt-2">
                        Panneau de Contrôle ⚙️
                    </button>
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
                <button onclick="switchTab('requests')" id="tab-requests" class="pb-3 font-bold text-warning border-b-2 border-warning px-2 transition">📩 Demandes (3)</button>
                <button onclick="switchTab('participants')" id="tab-participants" class="pb-3 font-bold text-gray-500 border-b-2 border-transparent hover:text-white px-2 transition">👥 Participants</button>
                <button onclick="switchTab('bracket')" id="tab-bracket" class="pb-3 font-bold text-gray-500 border-b-2 border-transparent hover:text-white px-2 transition">🏆 Arbre (Map)</button>
            </div>

            <div id="tab-content-requests" class="block fade-in">
                <div class="glass-card overflow-hidden rounded-xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black/30 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 font-medium">Candidat / Équipe</th>
                                <th class="p-4 font-medium">Date de demande</th>
                                <th class="p-4 font-medium">Winrate Joueur</th>
                                <th class="p-4 font-medium text-right">Décision</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
                            <tr class="hover:bg-white/5 transition">
                                <td class="p-4 font-bold text-white flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-700 rounded-full"></div> Team Alpha
                                </td>
                                <td class="p-4 text-gray-400">Il y a 2 heures</td>
                                <td class="p-4 text-success font-bold">68%</td>
                                <td class="p-4 text-right space-x-2">
                                    <button onclick="acceptRow(this)" class="bg-success/20 text-success border border-success/30 hover:bg-success hover:text-white px-3 py-1 rounded font-bold transition">Accepter</button>
                                    <button onclick="rejectRow(this)" class="bg-crimson/20 text-crimson border border-crimson/30 hover:bg-crimson hover:text-white px-3 py-1 rounded font-bold transition">Refuser</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-white/5 transition">
                                <td class="p-4 font-bold text-white flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-700 rounded-full"></div> Solo_Killer
                                </td>
                                <td class="p-4 text-gray-400">Hier</td>
                                <td class="p-4 text-warning font-bold">45%</td>
                                <td class="p-4 text-right space-x-2">
                                    <button onclick="acceptRow(this)" class="bg-success/20 text-success border border-success/30 hover:bg-success hover:text-white px-3 py-1 rounded font-bold transition">Accepter</button>
                                    <button onclick="rejectRow(this)" class="bg-crimson/20 text-crimson border border-crimson/30 hover:bg-crimson hover:text-white px-3 py-1 rounded font-bold transition">Refuser</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="tab-content-participants" class="hidden fade-in">
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
                <div class="clean-bracket-wrapper">
                    
                    <div class="bracket-header">
                        <h2>Tournament<br>Bracket</h2>
                        <div class="header-accent"></div>
                    </div>

                    <div class="flex mt-16">
                        
                        <div class="clean-round gap-4">
                            <div class="clean-match h-[110px]">
                                <div class="clean-team-node">OUSSAMA_PRO</div>
                                <div class="clean-team-node">PLAYER_02</div>
                                <div class="clean-connector"><div class="clean-green-accent"></div></div>
                            </div>
                            <div class="clean-match h-[110px]">
                                <div class="clean-team-node">TEAM ALPHA</div>
                                <div class="clean-team-node clean-team-empty">TBD</div>
                                <div class="clean-connector"><div class="clean-green-accent"></div></div>
                            </div>
                            <div class="clean-match h-[110px]">
                                <div class="clean-team-node">GHOST_RIDER</div>
                                <div class="clean-team-node clean-team-empty">TBD</div>
                                <div class="clean-connector"><div class="clean-green-accent"></div></div>
                            </div>
                            <div class="clean-match h-[110px]">
                                <div class="clean-team-node clean-team-empty">TBD</div>
                                <div class="clean-team-node clean-team-empty">TBD</div>
                                <div class="clean-connector"><div class="clean-green-accent"></div></div>
                            </div>
                        </div>

                        <div class="clean-round gap-4">
                            <div class="clean-match h-[236px]">
                                <div class="clean-team-node">OUSSAMA_PRO</div>
                                <div class="clean-team-node clean-team-empty">TBD</div>
                                <div class="clean-connector"><div class="clean-green-accent"></div></div>
                            </div>
                            <div class="clean-match h-[236px]">
                                <div class="clean-team-node clean-team-empty">TBD</div>
                                <div class="clean-team-node clean-team-empty">TBD</div>
                                <div class="clean-connector"><div class="clean-green-accent"></div></div>
                            </div>
                        </div>

                        <div class="clean-round gap-4">
                            <div class="clean-match h-[488px]">
                                <div class="clean-team-node">OUSSAMA_PRO</div>
                                <div class="clean-team-node clean-team-empty">TBD</div>
                                <div class="clean-connector"><div class="clean-green-accent"></div></div>
                            </div>
                        </div>

                        <div class="clean-round justify-center">
                            <div class="flex flex-col items-center gap-6">
                                <div class="clean-team-node text-center w-48">OUSSAMA_PRO</div>
                                <div class="text-6xl drop-shadow-md"></div>
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
        // SPA : Passer de la vue Dashboard à la vue d'un Tournoi
        function openTournamentView(title) {
            document.getElementById('view-dashboard').classList.add('hidden');
            document.getElementById('view-tournament').classList.remove('hidden');
            document.getElementById('panel-title').innerText = title;
        }

        function closeTournamentView() {
            document.getElementById('view-tournament').classList.add('hidden');
            document.getElementById('view-dashboard').classList.remove('hidden');
        }

        // SPA : Changer d'onglet dans le panneau du tournoi
        function switchTab(tabName) {
            // Cacher tous les contenus
            ['requests', 'participants', 'bracket'].forEach(t => {
                document.getElementById('tab-content-' + t).classList.add('hidden');
                document.getElementById('tab-' + t).className = "pb-3 font-bold text-gray-500 border-b-2 border-transparent hover:text-white px-2 transition";
            });
            
            // Afficher le bon
            document.getElementById('tab-content-' + tabName).classList.remove('hidden');
            
            // Style de l'onglet actif
            let colorClass = tabName === 'requests' ? 'text-warning border-warning' : (tabName === 'participants' ? 'text-cyan border-cyan' : 'text-gold border-gold');
            document.getElementById('tab-' + tabName).className = `pb-3 font-bold ${colorClass} border-b-2 px-2 transition`;
        }

        // Simulation d'acceptation/refus avec Toast
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
    </script>
</body>
</html>
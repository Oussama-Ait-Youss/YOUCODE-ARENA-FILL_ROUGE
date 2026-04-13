<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God Mode - Administration YouCode Arena</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#050505',
                        'bg-soft': '#0f1015',
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
            background-image: radial-gradient(circle at 50% 0%, rgba(220, 20, 60, 0.05) 0%, transparent 50%);
        }
        .glass-card {
            background: #0f1015;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { height: 6px; width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #DC143C; }

        /* Animations pour la SPA */
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .toast-enter { animation: slideInRight 0.3s forwards; }
        .toast-leave { animation: fadeOut 0.3s forwards; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { to { opacity: 0; visibility: hidden; } }
    </style>
</head>

<body class="text-gray-200 font-sans min-h-screen relative selection:bg-crimson selection:text-white flex overflow-hidden">

    <aside class="w-64 h-screen bg-[#050505] border-r border-white/10 flex flex-col hidden md:flex shrink-0">
        <div class="h-20 flex items-center justify-center border-b border-white/5">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 bg-crimson flex items-center justify-center transform skew-x-[-10deg]">
                    <span class="font-display font-bold text-black text-xl transform skew-x-[10deg]">Y</span>
                </div>
                <span class="text-xl font-display font-bold tracking-widest text-white group-hover:text-crimson transition-colors">YouCode - Arena
                </span>
            </a>
        </div>

        <nav class="flex-1 p-4 space-y-2 mt-4">
            <button onclick="switchTab('dashboard')" id="nav-dashboard" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-bold transition-all bg-white/10 text-white border-l-2 border-cyan shadow-[0_0_10px_rgba(0,240,255,0.2)]">
                📊 Vue Globale
            </button>
            <button onclick="switchTab('users')" id="nav-users" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-bold text-gray-500 hover:bg-white/5 hover:text-white transition-all border-l-2 border-transparent">
                👥 Utilisateurs
            </button>
            <button onclick="switchTab('tournaments')" id="nav-tournaments" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-bold text-gray-500 hover:bg-white/5 hover:text-white transition-all border-l-2 border-transparent">
                ⚔️ Tournois
            </button>
        </nav>

        <div class="p-4 border-t border-white/5 space-y-2">
            <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition">
                ⬅️ Retour au Hub
            </a>

            <button onclick="document.getElementById('logout-form').submit();" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded border border-white/10 text-gray-400 hover:text-crimson hover:border-crimson/50 transition">
                🚪 Sortir
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </aside>

    <main class="flex-1 h-screen overflow-y-auto relative bg-[#0a0a0f]">
        
        <header class="md:hidden h-16 border-b border-white/5 flex items-center justify-between px-4 bg-[#050505]">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white text-2xl">⬅️</a>
            <span class="font-display text-xl font-bold text-white tracking-widest">GOD MODE</span>
            <div class="w-6"></div> </header>

        <div class="max-w-[1200px] mx-auto p-6 lg:p-10">

            <section id="section-dashboard" class="tab-content fade-in block">
                <h1 class="text-3xl font-display font-bold text-white tracking-wider mb-6">TABLEAU DE BORD</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="glass-card p-6 border-t-4 border-t-cyan">
                        <div class="text-gray-500 font-bold uppercase tracking-widest text-sm mb-2">Total Utilisateurs</div>
                        <div class="text-5xl font-display font-bold text-white">{{ $totalUsers ?? 254 }}</div>
                    </div>
                    <div class="glass-card p-6 border-t-4 border-t-success">
                        <div class="text-gray-500 font-bold uppercase tracking-widest text-sm mb-2">Tournois Actifs</div>
                        <div class="text-5xl font-display font-bold text-success">{{ $activeTournaments ?? 12 }}</div>
                    </div>
                    <div class="glass-card p-6 border-t-4 border-t-gold">
                        <div class="text-gray-500 font-bold uppercase tracking-widest text-sm mb-2">Organisateurs</div>
                        <div class="text-5xl font-display font-bold text-gold">8</div>
                    </div>
                </div>

                <div class="glass-card p-6">
                    <h2 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Activité Récente (Simulation)</h2>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex gap-2">🟢 <span class="text-white">Orga_Master</span> a créé le tournoi "FIFA 26 Cup".</li>
                        <li class="flex gap-2">🔵 <span class="text-white">Admin</span> a promu "Sam_W" au rang de Jury.</li>
                        <li class="flex gap-2">🔴 <span class="text-white">ToxicPlayer</span> a été banni de la plateforme.</li>
                    </ul>
                </div>
            </section>

            <section id="section-users" class="tab-content fade-in hidden">
                <h1 class="text-3xl font-display font-bold text-white tracking-wider mb-6">GESTION DES UTILISATEURS</h1>
                
                <div class="glass-card overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black/30 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 font-medium">Pseudo</th>
                                <th class="p-4 font-medium">Rôle</th>
                                <th class="p-4 font-medium">Statut</th>
                                <th class="p-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
                            <tr class="hover:bg-white/5 transition" id="user-row-1">
                                <td class="p-4 font-bold text-white">Player_One</td>
                                <td class="p-4">
                                    <select onchange="updateRole(this)" class="bg-black/50 border border-white/10 text-gray-300 text-xs rounded px-2 py-1 outline-none">
                                        <option selected>Compétiteur</option>
                                        <option>Organisateur</option>
                                        <option>Jury</option>
                                    </select>
                                </td>
                                <td class="p-4"><span class="text-success text-xs font-bold" id="status-1">Actif</span></td>
                                <td class="p-4 text-right space-x-2">
                                    <button onclick="openUserModal('Player_One', 'player1@youcode.ma', 15, 10, 5)" class="text-cyan hover:text-white transition text-xs font-bold bg-cyan/10 px-2 py-1 rounded">Détails</button>
                                    <button onclick="toggleBan(1)" class="text-crimson hover:text-white transition text-xs font-bold border border-crimson/30 px-2 py-1 rounded" id="btn-ban-1">Bannir</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-white/5 transition" id="user-row-2">
                                <td class="p-4 font-bold text-white">Orga_Master</td>
                                <td class="p-4">
                                    <select onchange="updateRole(this)" class="bg-black/50 border border-white/10 text-gold text-xs rounded px-2 py-1 outline-none">
                                        <option>Compétiteur</option>
                                        <option selected>Organisateur</option>
                                        <option>Jury</option>
                                    </select>
                                </td>
                                <td class="p-4"><span class="text-success text-xs font-bold" id="status-2">Actif</span></td>
                                <td class="p-4 text-right space-x-2">
                                    <button onclick="openUserModal('Orga_Master', 'orga@youcode.ma', 2, 1, 1)" class="text-cyan hover:text-white transition text-xs font-bold bg-cyan/10 px-2 py-1 rounded">Détails</button>
                                    <button onclick="toggleBan(2)" class="text-crimson hover:text-white transition text-xs font-bold border border-crimson/30 px-2 py-1 rounded" id="btn-ban-2">Bannir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="section-tournaments" class="tab-content fade-in hidden">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-display font-bold text-white tracking-wider">SUPERVISION TOURNOIS</h1>
                    <button onclick="showToast('Création de tournoi (Simulation)', 'success')" class="bg-crimson hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-bold transition shadow-neon">
                        + Créer (Admin)
                    </button>
                </div>
                
                <div class="glass-card overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black/30 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 font-medium">Nom</th>
                                <th class="p-4 font-medium">Jeu</th>
                                <th class="p-4 font-medium">Organisateur</th>
                                <th class="p-4 font-medium">Statut</th>
                                <th class="p-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-white/5">
                            <tr class="hover:bg-white/5 transition" id="tour-row-1">
                                <td class="p-4 font-bold text-white">Winter Cup</td>
                                <td class="p-4 text-gray-400">Échecs</td>
                                <td class="p-4 text-gold">Orga_Master</td>
                                <td class="p-4"><span class="bg-success/10 text-success text-xs px-2 py-1 rounded-full">Ouvert</span></td>
                                <td class="p-4 text-right space-x-2">
                                    <button onclick="openTournamentModal('Winter Cup', 'Échecs', 'Ouvert')" class="text-cyan hover:text-white transition text-xs font-bold bg-cyan/10 px-2 py-1 rounded">Gérer</button>
                                    <button onclick="deleteTournament(1)" class="text-crimson hover:text-white transition text-xs font-bold border border-crimson/30 px-2 py-1 rounded">Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>

    <div id="modal-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        
        <div id="modal-user" class="glass-card w-full max-w-md p-6 hidden transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6 border-b border-white/10 pb-4">
                <h2 class="text-2xl font-display font-bold text-white tracking-wider">Détails Joueur</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-white text-xl">&times;</button>
            </div>
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gray-800 rounded-full mx-auto mb-2 border border-white/20"></div>
                <h3 id="modal-u-name" class="text-xl font-bold text-white">Nom</h3>
                <p id="modal-u-email" class="text-sm text-gray-400">email@test.com</p>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="bg-white/5 p-3 rounded">
                    <div class="text-xl font-bold text-white" id="modal-u-t">0</div>
                    <div class="text-xs text-gray-500 uppercase">Tournois</div>
                </div>
                <div class="bg-success/10 p-3 rounded">
                    <div class="text-xl font-bold text-success" id="modal-u-w">0</div>
                    <div class="text-xs text-success uppercase">Victoires</div>
                </div>
                <div class="bg-crimson/10 p-3 rounded">
                    <div class="text-xl font-bold text-crimson" id="modal-u-l">0</div>
                    <div class="text-xs text-crimson uppercase">Défaites</div>
                </div>
            </div>
        </div>

        <div id="modal-tournament" class="glass-card w-full max-w-lg p-6 hidden transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6 border-b border-white/10 pb-4">
                <h2 class="text-2xl font-display font-bold text-white tracking-wider" id="modal-t-name">Tournoi</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-white text-xl">&times;</button>
            </div>
            <div class="mb-4 flex gap-2">
                <span id="modal-t-game" class="bg-white/10 text-white text-xs px-2 py-1 rounded">Jeu</span>
                <span id="modal-t-status" class="bg-success/10 text-success text-xs px-2 py-1 rounded">Statut</span>
            </div>
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Participants (Simulation)</h3>
            <ul class="space-y-2 bg-black/30 p-4 rounded border border-white/5">
                <li class="flex justify-between items-center text-sm">
                    <span class="text-white">Player_One</span>
                    <button onclick="showToast('Joueur expulsé', 'warning')" class="text-xs text-crimson hover:text-white">Expulser</button>
                </li>
                <li class="flex justify-between items-center text-sm">
                    <span class="text-white">NoobMaster99</span>
                    <button onclick="showToast('Joueur expulsé', 'warning')" class="text-xs text-crimson hover:text-white">Expulser</button>
                </li>
            </ul>
        </div>

    </div>

    <div id="toast-container" class="fixed bottom-5 right-5 z-[60] flex flex-col gap-2 pointer-events-none"></div>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            document.getElementById('section-' + tabId).classList.remove('hidden');
            document.getElementById('section-' + tabId).classList.add('block');

            document.querySelectorAll('aside nav button').forEach(btn => {
                btn.className = "w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-bold text-gray-500 hover:bg-white/5 hover:text-white transition-all border-l-2 border-transparent";
            });
            let activeBtn = document.getElementById('nav-' + tabId);
            activeBtn.className = "w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-bold transition-all bg-white/10 text-white border-l-2 border-cyan shadow-[0_0_10px_rgba(0,240,255,0.2)]";
        }

        const overlay = document.getElementById('modal-overlay');
        const modalUser = document.getElementById('modal-user');
        const modalTour = document.getElementById('modal-tournament');

        function openUserModal(name, email, t, w, l) {
            document.getElementById('modal-u-name').innerText = name;
            document.getElementById('modal-u-email').innerText = email;
            document.getElementById('modal-u-t').innerText = t;
            document.getElementById('modal-u-w').innerText = w;
            document.getElementById('modal-u-l').innerText = l;
            
            overlay.classList.remove('hidden');
            modalUser.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                modalUser.classList.remove('scale-95');
            }, 10);
        }

        function openTournamentModal(name, game, status) {
            document.getElementById('modal-t-name').innerText = name;
            document.getElementById('modal-t-game').innerText = game;
            document.getElementById('modal-t-status').innerText = status;

            overlay.classList.remove('hidden');
            modalTour.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                modalTour.classList.remove('scale-95');
            }, 10);
        }

        function closeModal() {
            overlay.classList.add('opacity-0');
            modalUser.classList.add('scale-95');
            modalTour.classList.add('scale-95');
            setTimeout(() => {
                overlay.classList.add('hidden');
                modalUser.classList.add('hidden');
                modalTour.classList.add('hidden');
            }, 300);
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let color = type === 'success' ? 'bg-success/20 border-success/50 text-success' : 
                       (type === 'danger' ? 'bg-crimson/20 border-crimson/50 text-crimson' : 'bg-warning/20 border-warning/50 text-warning');
            
            toast.className = `px-4 py-3 rounded-lg border backdrop-blur-md font-bold text-sm shadow-lg toast-enter ${color}`;
            toast.innerText = message;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.replace('toast-enter', 'toast-leave');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function updateRole(select) {
            showToast("Rôle mis à jour : " + select.value, "success");
        }

        function toggleBan(id) {
            const btn = document.getElementById('btn-ban-' + id);
            const status = document.getElementById('status-' + id);
            
            if (btn.innerText.includes('Bannir')) {
                btn.innerText = "Débannir";
                btn.className = "text-white bg-crimson transition text-xs font-bold px-2 py-1 rounded";
                status.innerText = "Banni";
                status.className = "text-crimson text-xs font-bold";
                showToast("Utilisateur banni !", "danger");
            } else {
                btn.innerText = "Bannir";
                btn.className = "text-crimson hover:text-white transition text-xs font-bold border border-crimson/30 px-2 py-1 rounded";
                status.innerText = "Actif";
                status.className = "text-success text-xs font-bold";
                showToast("Utilisateur réintégré.", "success");
            }
        }

        function deleteTournament(id) {
            if(confirm("Détruire ce tournoi définitivement ?")) {
                document.getElementById('tour-row-' + id).remove();
                showToast("Tournoi supprimé avec succès.", "danger");
            }
        }
    </script>
</body>
</html>
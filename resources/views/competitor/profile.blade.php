<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - YouCode Arena</title>

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
                    fontFamily: { display: ['Teko', 'sans-serif'], sans: ['Outfit', 'sans-serif'] },
                    boxShadow: { 'neon': '0 0 20px rgba(220, 20, 60, 0.5)' }
                }
            }
        }
    </script>

    <style>
         body {
            background-color: #050505;
            background-image: radial-gradient(circle at 50% 0%, rgba(0, 240, 255, 0.05) 0%, transparent 50%);
        }
        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.7), rgba(10, 12, 18, 0.8));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        .glass-card:hover { border-color: rgba(0, 240, 255, 0.3); transform: translateY(-5px); }
    </style>
</head>

<body class="text-gray-200 font-sans min-h-screen relative flex flex-col selection:bg-cyan selection:text-black">

    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>

    <nav class="fixed top-0 w-full z-50 glass border-b border-white/5 h-20 flex justify-between items-center px-6">
        <a href="{{ route('dashboard') }}" class="text-2xl font-display font-bold text-white tracking-widest">YOUCODE ARENA</a>
        <div class="hidden md:flex gap-8">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">COMPETITION HUB</a>
            <a href="{{ route('competitor.tournaments.index') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">TOURNAMENTS</a>
            <a href="{{ route('competitor.profile') }}" class="text-cyan font-display tracking-wider text-lg relative">
                MON PROFIL
                <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-cyan shadow-[0_0_10px_rgba(0,240,255,0.5)]"></span>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="document.getElementById('logout-form').submit();" class="text-gray-400 hover:text-crimson font-bold text-sm tracking-widest uppercase transition-colors">Déconnexion</button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </nav>

    <main class="relative z-10 flex-grow max-w-7xl mx-auto w-full p-6 pt-32">
        
        <div class="flex flex-col md:flex-row items-center md:items-start gap-8 mb-12 bg-black/40 p-8 rounded-2xl border border-white/5 relative overflow-hidden">
            <div class="absolute inset-0 bg-cyan/5 animate-pulse"></div>
            
            <div class="w-32 h-32 rounded-full border-4 border-cyan p-1 relative z-10">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=transparent&color=fff&size=128" class="w-full h-full rounded-full object-cover bg-gray-800">
                <div class="absolute -bottom-2 -right-2 bg-gold text-black text-xs font-bold px-2 py-1 rounded border-2 border-[#050505]">LVL 12</div>
            </div>

            <div class="text-center md:text-left z-10">
                <h1 class="text-4xl font-display font-bold text-white tracking-wider uppercase">{{ $user->username }}</h1>
                <p class="text-cyan font-bold tracking-widest text-sm uppercase mb-4">Compétiteur Confirmé</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <span class="bg-white/5 border border-white/10 px-4 py-1 rounded-full text-sm text-gray-300">🎮 Main : Laravel</span>
                    <span class="bg-white/5 border border-white/10 px-4 py-1 rounded-full text-sm text-gray-300">🛡️ Main : Cybersec</span>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-display font-bold text-white mb-6 uppercase tracking-wider flex items-center gap-2">
            <span class="text-cyan">📊</span> Mes Statistiques
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-12">
            <div class="glass-card p-6 rounded-xl border-t-2 border-t-cyan">
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Winrate</div>
                <div class="text-4xl font-display font-bold text-white">68<span class="text-xl text-cyan">%</span></div>
            </div>
            <div class="glass-card p-6 rounded-xl border-t-2 border-t-crimson">
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Matchs Joués</div>
                <div class="text-4xl font-display font-bold text-white">24</div>
            </div>
            <div class="glass-card p-6 rounded-xl border-t-2 border-t-gold relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-6xl opacity-10">🏆</div>
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Tournois Gagnés</div>
                <div class="text-4xl font-display font-bold text-gold">2</div>
            </div>
            <div class="glass-card p-6 rounded-xl border-t-2 border-t-success">
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Défis Complétés</div>
                <div class="text-4xl font-display font-bold text-white">14<span class="text-xl text-gray-500">/20</span></div>
            </div>
        </div>

        <h2 class="text-2xl font-display font-bold text-white mb-6 uppercase tracking-wider flex items-center gap-2">
            <span class="text-crimson">⚔️</span> Mes Compétitions en cours
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($myTournaments as $tournament)
                <div class="glass-card rounded-xl p-6 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-cyan font-bold text-xs tracking-widest uppercase bg-cyan/10 px-2 py-1 rounded">{{ $tournament->game->name ?? 'Jeu' }}</span>
                            <span class="text-gray-500 text-xs font-bold uppercase">{{ $tournament->status }}</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $tournament->title }}</h3>
                        <p class="text-gray-400 text-sm mb-6">Prépare ton équipe, le prochain round approche.</p>
                    </div>

                    <a href="{{ route('competitor.tournaments.show', $tournament->id) }}" class="block w-full text-center bg-white/5 border border-white/10 hover:border-cyan hover:text-cyan text-white font-display tracking-widest px-6 py-3 rounded transition-colors group-hover:bg-cyan/5">
                        VOIR MON AVANCÉE ➔
                    </a>
                </div>
            @empty
                <div class="col-span-full border border-dashed border-white/20 rounded-xl p-12 text-center text-gray-500">
                    <div class="text-4xl mb-4">💤</div>
                    <div class="font-bold text-lg mb-2">Tu n'es inscrit à aucun tournoi.</div>
                    <a href="{{ route('competitor.tournaments.index') }}" class="text-cyan hover:underline font-bold">Explorer les tournois disponibles</a>
                </div>
            @endforelse
        </div>

    </main>
</body>
</html>
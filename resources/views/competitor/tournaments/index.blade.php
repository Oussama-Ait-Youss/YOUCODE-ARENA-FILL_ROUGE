<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournois - YouCode Arena</title>

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
                        success: '#22C55E'
                    },
                    fontFamily: {
                        display: ['Teko', 'sans-serif'],
                        sans: ['Outfit', 'sans-serif']
                    },
                    boxShadow: {
                        'neon': '0 0 20px rgba(220, 20, 60, 0.5)',
                    }
                }
            }
        }
    </script>

    <style>
         body {
            background-color: #050505;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(220, 20, 60, 0.08) 0%, transparent 25%), 
                radial-gradient(circle at 85% 30%, rgba(0, 240, 255, 0.05) 0%, transparent 25%);
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #DC143C; }

        .glass {
            background: rgba(15, 20, 30, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.7), rgba(10, 12, 18, 0.8));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(220, 20, 60, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5), 0 0 20px rgba(220, 20, 60, 0.2);
        }
    </style>
</head>

<body class="text-gray-200 font-sans min-h-screen relative selection:bg-crimson selection:text-white overflow-x-hidden flex flex-col">

    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>

   <nav class="fixed top-0 w-full z-50 glass border-b border-white/5">
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
                
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">
                    COMPETITION HUB
                </a>
                
                <a href="{{ route('competitor.tournaments.index') }}" class="text-white font-display tracking-wider text-lg relative">
                    TOURNAMENTS
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-crimson shadow-neon"></span>
                </a>

                    <a href="{{ route('competitor.profile') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">MON PROFIL</a>

                @if(auth()->user()->hasRole('Organisateur'))
                    <a href="{{ route('organizer.dashboard') }}" class="text-gold hover:text-white font-display tracking-wider text-lg transition-colors flex items-center gap-1">
                        👑 MES TOURNOIS
                    </a>
                @endif

                @if(auth()->user()->hasRole('Admin'))
                    <a href="{{ route('admin.dashboard') }}" class="text-cyan hover:text-white font-display tracking-wider text-lg transition-colors flex items-center gap-1">
                        🛡️ ADMINISTRATION
                    </a>
                @endif

            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 cursor-pointer group" onclick="document.getElementById('logout-form').submit();" title="Se déconnecter">
                    
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-crimson to-violet border-2 border-white/20 relative group-hover:scale-105 transition">
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-success rounded-full border-2 border-gray-900"></span>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=transparent&color=fff" class="w-full h-full object-cover rounded-full">
                    </div>

                    <span class="text-gray-400 group-hover:text-crimson font-bold text-sm tracking-widest uppercase transition-colors hidden sm:block">
                        Déconnexion
                    </span>
                    
                </div>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>

        </div>
    </nav>

    <main class="relative z-10 flex-grow max-w-7xl mx-auto w-full p-6 pt-28">
        
        <div class="text-center mb-10">
            <h1 class="text-5xl font-display font-bold text-white tracking-wider">L'ARÈNE DES <span class="text-crimson text-shadow-neon">TOURNOIS</span></h1>
            <p class="text-gray-400 mt-2">Rejoins les compétitions et prouve ta valeur.</p>
        </div>

        @if(session('error')) 
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl text-center font-bold mb-6">{{ session('error') }}</div> 
        @endif
        @if(session('success')) 
            <div class="bg-green-500/10 border border-green-500/20 text-green-500 p-4 rounded-xl text-center font-bold mb-6">{{ session('success') }}</div> 
        @endif

        <div class="flex flex-wrap justify-center gap-4 mb-10">
            <a href="{{ route('competitor.tournaments.index', ['filter' => 'all']) }}" 
               class="px-6 py-2 rounded-full font-display tracking-widest text-lg transition-all {{ $currentFilter == 'all' ? 'bg-crimson text-white shadow-neon border-transparent' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
               TOUS
            </a>
            <a href="{{ route('competitor.tournaments.index', ['filter' => 'ouvertes']) }}" 
               class="px-6 py-2 rounded-full font-display tracking-widest text-lg transition-all {{ $currentFilter == 'ouvertes' ? 'bg-crimson text-white shadow-neon border-transparent' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
               OUVERTES
            </a>
            <a href="{{ route('competitor.tournaments.index', ['filter' => 'a_venir']) }}" 
               class="px-6 py-2 rounded-full font-display tracking-widest text-lg transition-all {{ $currentFilter == 'a_venir' ? 'bg-crimson text-white shadow-neon border-transparent' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
               À VENIR
            </a>
            <a href="{{ route('competitor.tournaments.index', ['filter' => 'terminees']) }}" 
               class="px-6 py-2 rounded-full font-display tracking-widest text-lg transition-all {{ $currentFilter == 'terminees' ? 'bg-crimson text-white shadow-neon border-transparent' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
               TERMINÉES
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tournaments as $tournament)
                <div class="glass-card rounded-xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-gold font-display text-xl tracking-wider">{{ $tournament->game->name ?? 'Jeu Inconnu' }}</span>
                            <span class="bg-black/50 border border-white/10 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-widest {{ $tournament->status == 'Ouvert' ? 'text-success' : 'text-gray-400' }}">
                                {{ $tournament->status }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">{{ $tournament->title }}</h3>
                        
                        <div class="flex justify-between text-gray-400 text-sm mb-6 border-t border-white/5 pt-4">
                            <span class="flex items-center gap-1">📅 {{ \Carbon\Carbon::parse($tournament->event_date)->format('d M Y') }}</span>
                            <span class="flex items-center gap-1">👥 {{ $tournament->confirmed_registrations_count ?? 0 }} / {{ $tournament->max_capacity }} places</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('competitor.tournaments.show', $tournament->id) }}" class="block w-full text-center bg-white/5 border border-white/10 hover:border-cyan hover:text-cyan text-white font-display tracking-widest px-6 py-3 rounded transition-colors">
                            VOIR LES DÉTAILS
                        </a>
                        @php
                            // On vérifie si l'utilisateur connecté est déjà inscrit à ce tournoi
                            $isRegistered = \App\Models\Registration::where('user_id', auth()->id())
                                                ->where('tournament_id', $tournament->id)
                                                ->exists();
                        @endphp

                        @if($isRegistered)
                            <div class="block w-full text-center bg-success/10 text-success border border-success/30 font-display tracking-widest px-6 py-3 rounded cursor-default shadow-[0_0_15px_rgba(34,197,94,0.1)]">
                                ✅ DÉJÀ INSCRIT
                            </div>
                        @elseif($tournament->status == 'Ouvert' && ($tournament->confirmed_registrations_count ?? 0) < $tournament->max_capacity)
                            <a href="{{ route('competitor.teams.create', $tournament->id) }}" class="block w-full text-center bg-crimson hover:bg-red-700 text-white font-display tracking-widest px-6 py-3 rounded transition-colors shadow-neon">
                                {{ $tournament->game?->requiresTeamInvite() ? 'CRÉER L\'ÉQUIPE' : 'REJOINDRE LE TOURNOI' }}
                            </a>
                        @else
                            <div class="block w-full text-center bg-white/5 text-gray-500 font-display tracking-widest px-6 py-3 rounded cursor-not-allowed">
                                {{ ($tournament->confirmed_registrations_count ?? 0) >= $tournament->max_capacity ? 'COMPLET' : 'INSCRIPTIONS FERMÉES' }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full glass-card rounded-xl p-12 text-center text-gray-500">
                    <div class="text-4xl mb-4">🎮</div>
                    <div class="font-display font-bold text-xl">Aucun tournoi ne correspond à ce filtre pour le moment.</div>
                </div>
            @endforelse
        </div>
    </main>

    <footer class="glass border-t border-white/5 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center text-xs text-gray-500">
            <p>© 2026 YouCode Arena. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>

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
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">COMPETITION HUB</a>
                <a href="{{ route('competitor.tournaments.index') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">TOURNAMENTS</a>
                <a href="{{ route('competitor.profile') }}" class="text-white font-display tracking-wider text-lg relative">
                    MON PROFIL
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-crimson shadow-neon"></span>
                </a>

                @if(auth()->user()->hasRole('Organisateur'))
                    <a href="{{ route('organizer.dashboard') }}" class="text-gold hover:text-white font-display tracking-wider text-lg transition-colors flex items-center gap-1"> MES TOURNOIS</a>
                @endif

                @if(auth()->user()->hasRole('Admin'))
                    <a href="{{ route('admin.dashboard') }}" class="text-cyan hover:text-white font-display tracking-wider text-lg transition-colors flex items-center gap-1"> ADMINISTRATION</a>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 cursor-pointer group" onclick="document.getElementById('logout-form').submit();" title="Se déconnecter">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-crimson to-violet border-2 border-white/20 relative group-hover:scale-105 transition">
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-success rounded-full border-2 border-gray-900"></span>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=transparent&color=fff" class="w-full h-full object-cover rounded-full">
                    </div>
                    <span class="text-gray-400 group-hover:text-crimson font-bold text-sm tracking-widest uppercase transition-colors hidden sm:block">Déconnexion</span>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
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
                <p class="text-cyan font-bold tracking-widest text-sm uppercase mb-4">{{ $user->primaryRoleName() }}</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <span class="bg-white/5 border border-white/10 px-4 py-1 rounded-full text-sm text-gray-300">🎮 Victoires : {{ $stats['wins'] ?? 0 }}</span>
                    <span class="bg-white/5 border border-white/10 px-4 py-1 rounded-full text-sm text-gray-300"> Défaites : {{ $stats['losses'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        @if($pendingInvites->count() > 0)
            <h2 class="text-2xl font-display font-bold text-gold mb-6 uppercase tracking-wider flex items-center gap-2">
                <span class="animate-bounce">✉️</span> Invitations Reçues ({{ $pendingInvites->count() }})
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                @foreach($pendingInvites as $invite)
                    <div class="glass-card p-6 rounded-xl border-l-4 border-l-gold relative overflow-hidden group">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-gold font-bold text-xs tracking-widest uppercase bg-gold/10 px-2 py-1 rounded">Invitation Équipe</span>
                                <h3 class="text-xl font-bold text-white mt-3">{{ $invite->tournament->title }}</h3>
                                <p class="text-sm text-gray-400 mt-1">Tu as été invité à participer à ce tournoi.</p>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <form action="{{ route('competitor.teams.accept', $invite->tournament->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-success/20 hover:bg-success text-success hover:text-black font-bold py-2 rounded-lg transition-all uppercase text-xs tracking-widest">
                                    Accepter
                                </button>
                            </form>

                            <form action="{{ route('competitor.teams.decline', $invite->tournament->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-crimson/10 hover:bg-crimson text-crimson hover:text-white font-bold py-2 rounded-lg transition-all uppercase text-xs tracking-widest">
                                    Refuser
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($pendingApprovals->count() > 0)
            <h2 class="text-2xl font-display font-bold text-cyan mb-6 uppercase tracking-wider flex items-center gap-2">
                Inscriptions en attente ({{ $pendingApprovals->count() }})
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                @foreach($pendingApprovals as $registration)
                    <div class="glass-card p-6 rounded-xl border-l-4 border-l-cyan relative overflow-hidden group">
                        <span class="text-cyan font-bold text-xs tracking-widest uppercase bg-cyan/10 px-2 py-1 rounded">
                            Validation organisateur
                        </span>
                        <h3 class="text-xl font-bold text-white mt-3">{{ $registration->tournament->title }}</h3>
                        <p class="text-sm text-gray-400 mt-1">
                            Ton inscription est enregistrée. L'organisateur doit encore l'accepter.
                        </p>
                        @if($registration->team)
                            <p class="text-xs text-gray-500 mt-3">Équipe : {{ $registration->team->name }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        <h2 class="text-2xl font-display font-bold text-white mb-6 uppercase tracking-wider flex items-center gap-2">
            <span class="text-cyan"></span> Mes Statistiques
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-12">
            <div class="glass-card p-6 rounded-xl border-t-2 border-t-cyan">
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Winrate</div>
                <div class="text-4xl font-display font-bold text-white">{{ $stats['win_rate'] ?? 0 }}<span class="text-xl text-cyan">%</span></div>
            </div>
            <div class="glass-card p-6 rounded-xl border-t-2 border-t-crimson">
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Matchs Joués</div>
                <div class="text-4xl font-display font-bold text-white">{{ $stats['played_matches'] ?? 0 }}</div>
            </div>
            <div class="glass-card p-6 rounded-xl border-t-2 border-t-gold relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-6xl opacity-10">🏆</div>
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Tournois Actifs</div>
                <div class="text-4xl font-display font-bold text-gold">{{ $stats['active_tournaments'] ?? 0 }}</div>
            </div>
            <div class="glass-card p-6 rounded-xl border-t-2 border-t-success">
                <div class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Challenge Cards</div>
                <div class="text-4xl font-display font-bold text-white">{{ $stats['challenge_cards'] ?? 0 }}</div>
            </div>
        </div>

        <h2 class="text-2xl font-display font-bold text-white mb-6 uppercase tracking-wider flex items-center gap-2">
            <span class="text-gold"></span> Mes Challenges
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @forelse($upcomingChallenges ?? [] as $challenge)
                @php
                    $isTeamOne = $user->teams->contains('id', $challenge->team1_id);
                    $myTeam = $isTeamOne ? $challenge->team1 : $challenge->team2;
                    $opponent = $isTeamOne ? $challenge->team2 : $challenge->team1;
                @endphp
                <div class="glass-card rounded-xl p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-gold mb-2">{{ $challenge->tournament->title }}</p>
                    <h3 class="text-2xl font-display font-bold text-white mb-1">{{ $myTeam->name }} <span class="text-crimson">vs</span> {{ $opponent->name }}</h3>
                    <p class="text-sm text-gray-400 mb-4">{{ optional($challenge->played_at)->format('d/m/Y H:i') ?? 'Horaire à définir' }}</p>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest {{ $challenge->status === 'Terminé' ? 'bg-success/10 text-success' : 'bg-white/5 text-cyan' }}">
                        {{ $challenge->status }}
                    </span>
                </div>
            @empty
                <div class="col-span-full border border-dashed border-white/20 rounded-xl p-8 text-center text-gray-500">
                    Aucun duel planifié pour le moment.
                </div>
            @endforelse
        </div>

        <h2 class="text-2xl font-display font-bold text-white mb-6 uppercase tracking-wider flex items-center gap-2">
            <span class="text-crimson"></span> Mes Compétitions en cours
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($myTournaments ?? [] as $tournament)
                <div class="glass-card rounded-xl p-6 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-cyan font-bold text-xs tracking-widest uppercase bg-cyan/10 px-2 py-1 rounded">{{ $tournament->game->name ?? 'Jeu' }}</span>
                            <span class="text-gray-500 text-xs font-bold uppercase">{{ $tournament->status }}</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $tournament->title }}</h3>
                        <p class="text-gray-400 text-sm mb-6">Prépare ton équipe, surveille ton prochain match et l'avancée de l'arbre.</p>
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

    <footer class="glass border-t border-white/5 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center text-xs text-gray-500">
            <p>© 2026 YouCode Arena. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Quartier Général - YouCode Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
        :root { --bg: #0b0b0e; --card: #16161a; --orange: #f97316; --gray: #9ca3af; --border: #27272a; }

        body { background: var(--bg); font-family: 'Poppins', sans-serif; color: white; margin: 0; padding: 2rem 1rem; }
        .container { max-width: 1000px; margin: 0 auto; }

        /* Header / Profil */
        .profile-header { display: flex; justify-content: space-between; align-items: center; background: var(--card); padding: 2rem; border-radius: 15px; border: 1px solid var(--border); margin-bottom: 2rem; }
        .profile-info h1 { margin: 0; color: var(--orange); font-size: 2rem; text-transform: uppercase; font-weight: 900;}
        .profile-info p { margin: 5px 0 0 0; color: var(--gray); }
        
        /* Stats Rapides */
        .stats-grid { display: flex; gap: 15px; }
        .stat-box { background: rgba(255,255,255,0.03); padding: 15px 25px; border-radius: 10px; text-align: center; border: 1px solid var(--border); }
        .stat-value { font-size: 1.8rem; font-weight: 800; display: block; }
        .stat-label { font-size: 0.8rem; color: var(--gray); text-transform: uppercase; letter-spacing: 1px; }
        .text-green { color: #10b981; } .text-red { color: #ef4444; } .text-blue { color: #3b82f6; }

        /* Sections Grid */
        .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        @media (max-width: 768px) { .dashboard-grid { grid-template-columns: 1fr; } }
        
        h2 { font-size: 1.3rem; border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px; }

        /* Cartes Equipes & Matchs */
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; transition: border-color 0.2s; }
        .card:hover { border-color: var(--orange); }
        .card-title { font-size: 1.1rem; font-weight: 700; margin: 0 0 5px 0; color: white; }
        .card-subtitle { font-size: 0.9rem; color: var(--orange); margin: 0 0 15px 0; }
        
        .match-teams { display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.3); padding: 10px; border-radius: 8px; margin-top: 10px;}
        .team-name { font-weight: 600; width: 40%; text-align: center; }
        .vs { color: var(--gray); font-size: 0.8rem; font-weight: 900; background: var(--border); padding: 5px 10px; border-radius: 5px; }
        
        .empty-state { text-align: center; color: var(--gray); padding: 2rem; background: var(--card); border-radius: 12px; border: 1px dashed var(--border); }
        
        .nav-links { margin-bottom: 20px; }
        .nav-links a { color: var(--gray); text-decoration: none; margin-right: 15px; font-weight: 600; transition: color 0.2s; }
        .nav-links a:hover { color: var(--orange); }
    </style>
</head>
<body>

<div class="container">
    <div class="nav-links">
        <a href="{{ route('competitor.tournaments.index') }}"> Explorer les Tournois</a>
        <a href="{{ route('competitor.leaderboard') }}"> Voir le Leaderboard</a>
    </div>

    <div class="profile-header">
        <div class="profile-info">
            <h1>{{ auth()->user()->username }}</h1>
            <p>Compétiteur YouCode Arena</p>
        </div>
        
        @if($profile)
            @php
                $totalGames = $profile->games_won + $profile->games_loss;
                $winrate = $totalGames > 0 ? round(($profile->games_won / $totalGames) * 100) : 0;
            @endphp
            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-value text-green">{{ $profile->games_won }}</span>
                    <span class="stat-label">Victoires</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value text-red">{{ $profile->games_loss }}</span>
                    <span class="stat-label">Défaites</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value text-blue">{{ $winrate }}%</span>
                    <span class="stat-label">Winrate</span>
                </div>
            </div>
        @endif
    </div>

    <div class="dashboard-grid">
        <div>
            <h2> Mes Équipes & Inscriptions</h2>
            @forelse($myTeams as $team)
                <div class="card">
                    <h3 class="card-title">{{ $team->name }}</h3>
                    <p class="card-subtitle">Engagé dans : {{ $team->tournament->title ?? 'Tournoi Inconnu' }}</p>
                </div>
            @empty
                <div class="empty-state">
                    Tu n'es encore inscrit dans aucune équipe.<br>
                    <a href="{{ route('competitor.tournaments.index') }}" style="color: var(--orange); text-decoration: none; margin-top: 10px; display: inline-block;">Trouver un tournoi</a>
                </div>
            @endforelse
        </div>

        <div>
            <h2>⚔️ Mes Prochains Affrontements</h2>
            @forelse($upcomingMatches as $match)
                <div class="card">
                    <p class="card-subtitle" style="color: var(--gray); font-size: 0.8rem; margin-bottom: 5px;">
                         {{ \Carbon\Carbon::parse($match->played_at)->format('d M Y à H:i') }}
                    </p>
                    <h3 class="card-title" style="font-size: 0.9rem;">{{ $match->tournament->title ?? 'Tournoi' }}</h3>
                    
                    <div class="match-teams">
                        <span class="team-name" style="{{ $myTeams->contains('id', $match->team1_id) ? 'color: var(--orange);' : '' }}">
                            {{ $match->team1->name ?? 'TBD' }}
                        </span>
                        
                        <span class="vs">VS</span>
                        
                        <span class="team-name" style="{{ $myTeams->contains('id', $match->team2_id) ? 'color: var(--orange);' : '' }}">
                            {{ $match->team2->name ?? 'TBD' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    Aucun match programmé pour le moment. Entraîne-toi en attendant !
                </div>
            @endforelse
        </div>
    </div>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - YouCode Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
        :root { --bg: #0b0b0e; --card: #16161a; --orange: #f97316; --gray: #9ca3af; --border: #27272a; }

        body { background: var(--bg); font-family: 'Poppins', sans-serif; color: white; margin: 0; padding: 2rem 1rem; }
        .container { max-width: 800px; margin: 0 auto; }

        .header { text-align: center; margin-bottom: 3rem; }
        .title { font-size: 2.5rem; font-weight: 900; color: var(--orange); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 2px;}
        .subtitle { color: var(--gray); font-size: 1.1rem; }

        .leaderboard { display: flex; flex-direction: column; gap: 10px; }

        .rank-card {
            background: var(--card); border: 1px solid var(--border);
            padding: 1rem 1.5rem; border-radius: 15px;
            display: flex; justify-content: space-between; align-items: center;
            transition: transform 0.2s, border-color 0.2s;
        }
        .rank-card:hover { transform: translateX(5px); border-color: var(--orange); }

        .rank-info { display: flex; align-items: center; gap: 20px; }
        .rank-number { font-size: 1.5rem; font-weight: 900; width: 40px; text-align: center; color: var(--gray); }
        .player-name { font-size: 1.2rem; font-weight: 700; }

        .stats { display: flex; gap: 20px; text-align: center; }
        .stat-box { display: flex; flex-direction: column; min-width: 70px; }
        .stat-value { font-size: 1.2rem; font-weight: 800; }
        .stat-label { font-size: 0.7rem; color: var(--gray); text-transform: uppercase; letter-spacing: 1px; }

        /* Styles Spéciaux pour le TOP 3 */
        .rank-1 { border-color: #fbbf24; background: linear-gradient(90deg, rgba(251,191,36,0.1) 0%, var(--card) 100%); }
        .rank-1 .rank-number, .rank-1 .player-name { color: #fbbf24; text-shadow: 0 0 10px rgba(251,191,36,0.3); }

        .rank-2 { border-color: #94a3b8; background: linear-gradient(90deg, rgba(148,163,184,0.1) 0%, var(--card) 100%); }
        .rank-2 .rank-number { color: #94a3b8; }

        .rank-3 { border-color: #b45309; background: linear-gradient(90deg, rgba(180,83,9,0.1) 0%, var(--card) 100%); }
        .rank-3 .rank-number { color: #b45309; }

        .is-me { border-left: 4px solid var(--orange); }
        
        .back-link { color: var(--gray); text-decoration: none; display: inline-block; margin-bottom: 20px; transition: color 0.2s; }
        .back-link:hover { color: var(--orange); }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('dashboard') }}" class="back-link">← Retour au Quartier Général</a>

    <div class="header">
        <h1 class="title">🏆 Hall of Fame</h1>
        <p class="subtitle">Le Top 10 des meilleurs gladiateurs de YouCode Arena</p>
    </div>

    <div class="leaderboard">
        @forelse($leaders as $index => $leader)
            @php
                $rankClass = '';
                if($index == 0) $rankClass = 'rank-1';
                elseif($index == 1) $rankClass = 'rank-2';
                elseif($index == 2) $rankClass = 'rank-3';

                // On met en valeur la ligne si c'est l'utilisateur connecté
                $isMeClass = (auth()->id() == $leader->id) ? 'is-me' : '';
            @endphp

            <div class="rank-card {{ $rankClass }} {{ $isMeClass }}">
                <div class="rank-info">
                    <div class="rank-number">
                        @if($index == 0) 🥇
                        @elseif($index == 1) 🥈
                        @elseif($index == 2) 🥉
                        @else #{{ $index + 1 }}
                        @endif
                    </div>
                    <div class="player-name">
                        {{ $leader->username }}
                        @if(auth()->id() == $leader->id) 
                            <span style="font-size: 0.8rem; color: var(--orange); margin-left: 10px; background: rgba(249, 115, 22, 0.2); padding: 2px 8px; border-radius: 10px;">C'est toi !</span> 
                        @endif
                    </div>
                </div>

                <div class="stats">
                    <div class="stat-box">
                        <span class="stat-value" style="color: #10b981;">{{ $leader->games_won }}</span>
                        <span class="stat-label">Victoires</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-value" style="color: #ef4444;">{{ $leader->games_loss }}</span>
                        <span class="stat-label">Défaites</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="rank-card" style="justify-content: center; color: var(--gray);">
                Aucun joueur classé pour le moment. Allez jouer des matchs !
            </div>
        @endforelse
    </div>
</div>

</body>
</html>
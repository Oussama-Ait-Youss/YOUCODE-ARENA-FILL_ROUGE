<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Inscriptions - YouCode Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
        
        :root { 
            --bg: #0b0b0e; 
            --card: #16161a; 
            --orange: #f97316; 
            --gray: #9ca3af; 
            --border: #27272a;
        }

        body { 
            background: var(--bg); 
            font-family: 'Poppins', sans-serif; 
            color: white; 
            padding: 2rem; 
            margin: 0;
        }

        .container { max-width: 900px; margin: 0 auto; }

        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 3rem; 
        }

        .header h1 { font-size: 2rem; font-weight: 800; margin: 0; }

        .btn-back { 
            color: var(--orange); 
            text-decoration: none; 
            font-size: 0.9rem; 
            font-weight: 600;
            border: 1px solid var(--orange);
            padding: 8px 16px;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: var(--orange);
            color: white;
        }

        .team-card { 
            background: var(--card); 
            border: 1px solid var(--border); 
            border-radius: 20px; 
            padding: 2rem; 
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.3s;
        }

        .team-card:hover { border-color: var(--orange); }

        .team-info p { margin: 0; color: var(--gray); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        .team-info h3 { margin: 5px 0; color: var(--orange); font-size: 1.5rem; font-weight: 800; }
        .tournament-name { color: white !important; font-size: 1.1rem !important; text-transform: none !important; margin-top: 10px !important; display: block; }

        .status-badge { 
            background: rgba(16, 185, 129, 0.1); 
            color: #10b981; 
            padding: 6px 16px; 
            border-radius: 50px; 
            font-size: 0.75rem; 
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .empty-state {
            text-align: center; 
            padding: 4rem; 
            background: var(--card); 
            border-radius: 20px; 
            border: 1px dashed var(--border);
        }

        .empty-state p { color: var(--gray); margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mes Inscriptions</h1>
            <a href="{{ route('competitor.tournaments.index') }}" class="btn-back">← Retour à l'Arène</a>
        </div>

        @forelse($registrations as $reg)
            <div class="team-card">
                <div class="team-info">
                    <p>{{ $reg->tournament->game->name ?? 'Jeu' }} • {{ $reg->tournament->category->name ?? 'Catégorie' }}</p>
                    
                    {{-- On affiche la première équipe trouvée pour ce tournoi --}}
                    <h3>Équipe : {{ $reg->tournament->teams->first()->name ?? 'Solo' }}</h3>
                    
                    <span class="tournament-name">Tournoi : <strong>{{ $reg->tournament->title }}</strong></span>
                </div>
                <div>
                    <span class="status-badge">{{ $reg->status }}</span>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p>Vous n'êtes inscrit à aucun tournoi pour le moment.</p>
                <a href="{{ route('competitor.tournaments.index') }}" class="btn-back" style="background: var(--orange); color: white;">Trouver un Tournoi</a>
            </div>
        @endforelse
    </div>
</body>
</html>
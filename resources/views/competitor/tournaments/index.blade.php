<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Arène - YouCode Arena</title>
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
            margin: 0;
            padding: 1.5rem; /* Padding réduit pour mobile */
        }

        .container { max-width: 1200px; margin: 0 auto; }

        .header-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header-section h1 { font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--orange); }
        .header-section p { color: var(--gray); font-size: 0.9rem; margin-top: 0; }

        /* --- SYSTÈME DE FILTRES --- */
        .filters {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap; /* Permet aux boutons de passer à la ligne sur petit écran */
            margin-bottom: 2rem;
        }

        .filter-btn {
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--gray);
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-btn:hover { border-color: var(--orange); color: white; }
        
        .filter-btn.active {
            background: var(--orange);
            color: white;
            border-color: var(--orange);
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
        }

        /* --- GRILLE MOBILE FIRST --- */
        .tournament-grid {
            display: grid;
            /* 1 colonne par défaut (Mobile) */
            grid-template-columns: 1fr; 
            gap: 1.5rem;
        }

        /* À partir d'une tablette (768px), on passe à 2 colonnes, sur PC (1024px) 3 colonnes */
        @media (min-width: 768px) { .tournament-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .tournament-grid { grid-template-columns: repeat(3, 1fr); } }

        /* --- CARTE TOURNOI --- */
        .t-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s;
        }
        
        .t-card:hover { transform: translateY(-5px); border-color: var(--orange); }

        .t-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
        .t-game { color: var(--orange); font-size: 0.8rem; font-weight: 800; text-transform: uppercase; }
        .t-status { background: #27272a; font-size: 0.7rem; padding: 4px 10px; border-radius: 10px; font-weight: 600; }
        
        .t-title { font-size: 1.2rem; font-weight: 800; margin: 0 0 10px 0; }
        
        .t-info { display: flex; justify-content: space-between; color: var(--gray); font-size: 0.85rem; margin-bottom: 1.5rem; }

        .btn-join {
            display: block; width: 100%; text-align: center; text-decoration: none;
            background: var(--orange); color: white; padding: 12px; border-radius: 12px;
            font-weight: 700; font-size: 0.9rem; transition: 0.3s;
        }
        .btn-join:hover { background: #ea580c; }
        
        .btn-disabled {
            display: block; width: 100%; text-align: center; text-decoration: none;
            background: #3f3f46; color: #a1a1aa; padding: 12px; border-radius: 12px;
            font-weight: 700; font-size: 0.9rem; cursor: not-allowed;
        }

        /* Messages de succès/erreur */
        .alert { padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; text-align: center; font-weight: 600; font-size: 0.9rem; }
        .alert-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
        .alert-success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    </style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <h1>L'Arène</h1>
        <p>Rejoignez les meilleurs tournois de YouCode</p>
    </div>

    @if(session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="filters">
        <a href="{{ route('competitor.tournaments.index', ['filter' => 'all']) }}" 
           class="filter-btn {{ $currentFilter == 'all' ? 'active' : '' }}">Tous</a>
           
        <a href="{{ route('competitor.tournaments.index', ['filter' => 'ouvertes']) }}" 
           class="filter-btn {{ $currentFilter == 'ouvertes' ? 'active' : '' }}">Ouvertes (Places dispo)</a>
           
        <a href="{{ route('competitor.tournaments.index', ['filter' => 'a_venir']) }}" 
           class="filter-btn {{ $currentFilter == 'a_venir' ? 'active' : '' }}">À venir</a>
           
        <a href="{{ route('competitor.tournaments.index', ['filter' => 'terminees']) }}" 
           class="filter-btn {{ $currentFilter == 'terminees' ? 'active' : '' }}">Terminées</a>
    </div>

    <div class="tournament-grid">
        @forelse($tournaments as $tournament)
            <div class="t-card">
                <div>
                    <div class="t-header">
                        <span class="t-game">{{ $tournament->game->name ?? 'Jeu Inconnu' }}</span>
                        <span class="t-status">{{ $tournament->status }}</span>
                    </div>
                    <h3 class="t-title">{{ $tournament->title }}</h3>
                    
                    <div class="t-info">
                        <span>📅 {{ \Carbon\Carbon::parse($tournament->event_date)->format('d M. Y - H:i') }}</span>
                        <span>👥 {{ $tournament->teams_count ?? 0 }} / {{ $tournament->max_capacity }} places</span>
                    </div>
                </div>

                @if($tournament->status == 'À venir' && ($tournament->teams_count ?? 0) < $tournament->max_capacity)
                    <a href="{{ route('competitor.teams.create', $tournament->id) }}" class="btn-join">Créer une équipe & Rejoindre</a>
                @else
                    <div class="btn-disabled">Inscriptions Fermées</div>
                @endif
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; color: var(--gray); padding: 3rem;">
                <p>Aucun tournoi ne correspond à ce filtre pour le moment.</p>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>
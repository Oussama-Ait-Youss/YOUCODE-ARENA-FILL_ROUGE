<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arène des Tournois - YouCode Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap');

        :root {
            --bg-page: #0b0b0e; /* On passe sur un fond très sombre pour l'immersion gaming */
            --bg-card: #16161a;
            --text-white: #ffffff;
            --text-gray: #9ca3af;
            --accent-orange: #f97316;
            --accent-orange-hover: #ea580c;
            --border-dark: #27272a;
        }

        body {
            background-color: var(--bg-page);
            font-family: 'Poppins', sans-serif;
            color: var(--text-white);
            margin: 0;
            padding: 2rem;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 900;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: -1px;
            background: linear-gradient(to right, #f97316, #fb923c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: var(--text-gray);
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }

        /* --- GRID DES CARTES --- */
        .tournaments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
        }

        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 20px;
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(249, 115, 22, 0.15);
            border-color: rgba(249, 115, 22, 0.4);
        }

        /* Badge de catégorie (ex: E-sport) */
        .badge-category {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-white);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .card-game {
            color: var(--accent-orange);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0 0 1rem 0;
            line-height: 1.2;
        }

        .card-details {
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .detail-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .detail-icon { font-size: 1.1rem; }

        /* Jauge de places */
        .quota-bar {
            width: 100%;
            height: 6px;
            background: #333;
            border-radius: 10px;
            margin-top: 1rem;
            overflow: hidden;
        }

        .quota-fill {
            height: 100%;
            background: var(--accent-orange);
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        .quota-fill.full { background: #ef4444; } /* Devient rouge si plein */

        .quota-text {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--text-gray);
            margin-top: 0.3rem;
            font-weight: 600;
        }

        /* Bouton d'action */
        .btn-join {
            display: block; /* La correction magique pour les balises <a> */
            width: 100%;
            box-sizing: border-box;
            background-color: var(--accent-orange);
            color: var(--text-white);
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease; /* Transition plus douce */
            text-align: center;
            text-decoration: none;
        }

        .btn-join:hover { 
            background-color: var(--accent-orange-hover);
            transform: translateY(-2px); /* Petit effet de soulèvement */
            box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3); /* Lueur orange */
        }

        .btn-disabled {
            display: block;
            width: 100%;
            box-sizing: border-box;
            background-color: #3f3f46;
            color: #a1a1aa;
            cursor: not-allowed;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
            text-align: center;
        }
        .btn-disabled:hover { 
            background-color: #3f3f46; 
            transform: none;
            box-shadow: none;
        }

    </style>
</head>
<body>

    <div class="container">
        
        <div class="header">
            <h1>Arène des Compétiteurs</h1>
            <p>Découvrez les événements à venir. Formez votre équipe. Dominez le classement.</p>
        </div>

        <div class="tournaments-grid">
            @forelse($tournaments as $tournament)
                @php
                    $percentage = ($tournament->registered_count / $tournament->max_capacity) * 100;
                    if($percentage > 100) $percentage = 100;
                @endphp

                <div class="card">
                    <span class="badge-category">{{ $tournament->category->name }}</span>
                    
                    <div class="card-game">{{ $tournament->game->name }}</div>
                    <h2 class="card-title">{{ $tournament->title }}</h2>
                    
                    <div class="card-details">
                        <div class="detail-row">
                            <span class="detail-icon">📅</span> 
                            {{ $tournament->event_date->format('d M Y - H:i') }}
                        </div>
                        <div class="detail-row">
                            <span class="detail-icon">👑</span> 
                            Org: {{ $tournament->organizer->username }}
                        </div>

                        <div class="quota-bar">
                            <div class="quota-fill {{ $tournament->is_full ? 'full' : '' }}" style="width: {{ $percentage }}%;"></div>
                        </div>
                        <div class="quota-text">
                            <span>Places prises: {{ $tournament->registered_count }}</span>
                            <span>Max: {{ $tournament->max_capacity }}</span>
                        </div>
                    </div>

                    @if($tournament->can_register)
                        <a href="{{ route('competitor.teams.create', $tournament->id) }}" class="btn-join">Créer une équipe & Rejoindre</a>
                    @else
                        <button class="btn-join btn-disabled" disabled>
                            {{ $tournament->is_full ? 'Tournoi Complet' : 'Inscriptions Fermées' }}
                        </button>
                    @endif

                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; color: var(--text-gray); padding: 3rem; background: var(--bg-card); border-radius: 20px;">
                    <h2>Aucun tournoi disponible pour le moment...</h2>
                    <p>Les organisateurs préparent l'arène. Revenez plus tard !</p>
                </div>
            @endforelse
        </div>

    </div>

</body>
</html>
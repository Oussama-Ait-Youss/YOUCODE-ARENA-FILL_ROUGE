<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Équipe - {{ $tournament->title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap');

        :root {
            --bg-page: #0b0b0e;
            --bg-card: #16161a;
            --accent-orange: #f97316;
            --text-white: #ffffff;
            --text-gray: #9ca3af;
            --border-dark: #27272a;
        }

        body {
            background-color: var(--bg-page);
            font-family: 'Poppins', sans-serif;
            color: var(--text-white);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 2rem;
        }

        .team-container {
            width: 100%;
            max-width: 500px;
            background: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .header { text-align: center; margin-bottom: 2rem; }
        .header h1 { font-size: 1.8rem; font-weight: 800; margin: 0; }
        
        .tournament-badge {
            display: inline-block;
            background: rgba(249, 115, 22, 0.1);
            color: var(--accent-orange);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-top: 0.5rem;
            border: 1px solid rgba(249, 115, 22, 0.2);
        }

        .format-label {
            color: var(--accent-orange);
            font-size: 0.9rem;
            margin-top: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
        }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-bottom: 0.6rem;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            background: #0b0b0e;
            border: 2px solid var(--border-dark);
            border-radius: 12px;
            padding: 1rem;
            color: white;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent-orange);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
        }

        .btn-submit {
            width: 100%;
            background: var(--accent-orange);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: #ea580c;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-gray);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }
        .back-link:hover { color: white; }

        .helper-text {
            color: var(--text-gray);
            font-size: 0.7rem;
            display: block;
            margin-top: 5px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="team-container">
        <div class="header">
            <h1>Nouvelle Équipe</h1>
            <div class="tournament-badge">🏆 {{ $tournament->title }}</div>
            <span class="format-label">
                Format : {{ $isDuo ? '👥 DUO (2 joueurs)' : '👤 SOLO' }}
            </span>
        </div>

        <form action="{{ route('competitor.teams.store', $tournament->id) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Nom de l'équipe (Gamertag ou Team Name)</label>
                <input type="text" name="name" id="name" required placeholder="Ex: Titans de YouCode" autofocus>
            </div>

            @if($isDuo)
                <div class="form-group">
                    <label for="partner_email">Email du coéquipier (Optionnel)</label>
                    <input type="email" name="partner_email" id="partner_email" placeholder="joueur2@youcode.ma">
                    <span class="helper-text">* Une invitation sera envoyée à ce joueur pour rejoindre votre équipe.</span>
                </div>
            @endif

            <button type="submit" class="btn-submit">Valider l'Inscription</button>
        </form>

        <a href="{{ route('competitor.tournaments.index') }}" class="back-link">← Annuler et revenir</a>
    </div>

</body>
</html>
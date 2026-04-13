<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Dashboard - YouCode Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
        :root { 
            --bg: #0b0b0e; 
            --card: #16161a; 
            --orange: #f97316; 
            --gray: #9ca3af; 
            --border: #27272a; 
            --green: #10b981; 
            --red: #ef4444;
        }

        body { background: var(--bg); font-family: 'Poppins', sans-serif; color: white; margin: 0; padding: 1rem; }
        .container { max-width: 900px; margin: 0 auto; }

        /* HEADER PROFIL */
        .profile-header {
            background: linear-gradient(135deg, var(--card) 0%, #1f1f23 100%);
            padding: 2rem; border-radius: 24px; border: 1px solid var(--border);
            margin-bottom: 2rem; display: flex; align-items: center; gap: 20px;
        }
        
        .avatar { 
            width: 80px; height: 80px; 
            background: var(--orange); 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 2.2rem; font-weight: 900; 
            box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
            border: 3px solid var(--bg);
        }

        .user-info h2 { margin: 0; font-size: 1.5rem; }
        .user-info p { margin: 5px 0 0; color: var(--gray); font-size: 0.9rem; }

        /* STATS GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 2rem; }
        .stat-card { background: var(--card); padding: 1.2rem; border-radius: 15px; border: 1px solid var(--border); text-align: center; }
        .stat-value { display: block; font-size: 1.5rem; font-weight: 800; color: var(--orange); }
        .stat-label { font-size: 0.75rem; color: var(--gray); text-transform: uppercase; letter-spacing: 1px; }

        /* LISTE DES INSCRIPTIONS */
        .section-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 10px; }
        
        .reg-link { text-decoration: none; color: inherit; display: block; margin-bottom: 12px; }
        
        .reg-card {
            background: var(--card); border: 1px solid var(--border);
            padding: 1.2rem; border-radius: 18px;
            display: flex; justify-content: space-between; align-items: center;
            transition: all 0.3s ease;
        }
        
        .reg-link:hover .reg-card { border-color: var(--orange); transform: translateX(5px); }

        .reg-info h3 { margin: 0; font-size: 1rem; }
        .reg-info p { margin: 4px 0 0; font-size: 0.8rem; color: var(--gray); }
        
        /* Badges de statut robustes */
        .status-badge { font-size: 0.7rem; padding: 5px 12px; border-radius: 50px; font-weight: 700; text-transform: uppercase; }
        
        /* Sélecteurs basés sur le texte contenu pour éviter les bugs d'accents */
        .status-badge.status-confirmé { background: rgba(16, 185, 129, 0.1); color: var(--green); }
        .status-badge.status-en-attente { background: rgba(249, 115, 22, 0.1); color: var(--orange); }
        .status-badge.status-refusé { background: rgba(239, 68, 68, 0.1); color: var(--red); }

        /* Navigation */
        .nav-back { display: inline-block; margin-bottom: 1.5rem; color: var(--gray); text-decoration: none; font-size: 0.85rem; transition: 0.3s; }
        .nav-back:hover { color: var(--orange); }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('competitor.tournaments.index') }}" class="nav-back">← Retour à l'Arène</a>

    <div class="profile-header">
        <div class="avatar">{{ strtoupper(substr($user->username, 0, 1)) }}</div>
        <div class="user-info">
            <h2>{{ $user->username }}</h2>
            <p>Membre de l'élite YouCode Arena</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-value">{{ $stats['total_tournaments'] }}</span>
            <span class="stat-label">Tournois</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">{{ $stats['pending_invitations'] }}</span>
            <span class="stat-label">Invitations</span>
        </div>
    </div>

    <div class="section-title">🏆 Mes Inscriptions</div>

    @forelse($registrations as $reg)
        <a href="{{ route('competitor.tournaments.show', $reg->tournament->id) }}" class="reg-link">
            <div class="reg-card">
                <div class="reg-info">
                    <h3>{{ $reg->tournament->title }}</h3>
                    <p>{{ $reg->tournament->game->name }} • {{ \Carbon\Carbon::parse($reg->registration_date)->format('d M Y') }}</p>
                </div>
                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $reg->status)) }}">
                    {{ $reg->status }}
                </span>
            </div>
        </a>
    @empty
        <div style="text-align: center; padding: 3rem; background: var(--card); border-radius: 20px; border: 1px dashed var(--border);">
            <p style="color: var(--gray); margin-bottom: 1rem;">Vous n'avez pas encore d'inscriptions.</p>
            <a href="{{ route('competitor.tournaments.index') }}" style="color: var(--orange); font-weight: 700; text-decoration: none; border: 1px solid var(--orange); padding: 8px 20px; border-radius: 10px;">Trouver un tournoi</a>
        </div>
    @endforelse
</div>

</body>
</html>
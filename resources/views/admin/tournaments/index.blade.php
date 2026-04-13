<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modération des Tournois - God Mode</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
        :root { --bg: #0b0b0e; --card: #16161a; --admin-accent: #dc2626; --gold: #fbbf24; --gray: #9ca3af; --border: #27272a; --text: #f3f4f6; }
        
        body { background: var(--bg); font-family: 'Poppins', sans-serif; color: var(--text); margin: 0; padding: 2rem 1rem; }
        .container { max-width: 1200px; margin: 0 auto; }
        
        a { text-decoration: none; color: var(--gray); font-weight: 600; transition: color 0.2s;}
        a:hover { color: white; }
        
        .header { margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem; }
        .title { font-size: 2.5rem; color: var(--admin-accent); margin: 0; font-weight: 900; text-transform: uppercase; }
        
        /* Table Styles */
        .table-container { overflow-x: auto; background: var(--card); border-radius: 12px; border: 1px solid var(--border); }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: rgba(220, 38, 38, 0.05); color: var(--admin-accent); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;}
        tr:last-child td { border-bottom: none; }
        tr:hover { background: rgba(255, 255, 255, 0.02); }
        
        .status-badge { padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; }
        .status-open { background: rgba(16, 185, 129, 0.15); color: #10b981; }
        .status-closed { background: rgba(220, 38, 38, 0.15); color: var(--admin-accent); }
        
        /* Buttons */
        .btn-danger { background: rgba(220, 38, 38, 0.1); color: var(--admin-accent); border: 1px solid var(--admin-accent); padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: 0.2s; }
        .btn-danger:hover { background: var(--admin-accent); color: white; }
        
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; }
        .empty-state { text-align: center; padding: 3rem; color: var(--gray); font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.dashboard') }}">← Retour au Centre de Commandement</a>
        </div>

        <div class="header">
            <h1 class="title">⚔️ Modération des Tournois</h1>
            <p style="color: var(--gray);">Supervision globale de tous les événements créés sur YouCode Arena.</p>
        </div>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Titre du Tournoi</th>
                        <th>Jeu</th>
                        <th>Organisateur</th>
                        <th>Statut</th>
                        <th>Date prévue</th>
                        <th>Action (God Mode)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tournaments as $tournament)
                    <tr>
                        <td style="font-weight: 800; color: white;">{{ $tournament->title }}</td>
                        <td style="color: var(--gold);">{{ $tournament->game->name ?? 'Jeu inconnu' }}</td>
                        <td style="color: var(--gray);">{{ $tournament->organizer->username ?? 'Inconnu' }}</td>
                        <td>
                            <span class="status-badge {{ $tournament->status === 'Ouvert' ? 'status-open' : 'status-closed' }}">
                                {{ $tournament->status }}
                            </span>
                        </td>
                        <td style="font-size: 0.9rem; color: var(--gray);">
                            {{ \Carbon\Carbon::parse($tournament->event_date)->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <form action="{{ route('admin.tournaments.destroy', $tournament->id) }}" method="POST" onsubmit="return confirm('ALERTE ROUGE : Es-tu sûr de vouloir détruire ce tournoi ? Tous les matchs et équipes associés seront supprimés. Action irréversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger">Détruire 💥</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">Aucun tournoi n'a été créé pour le moment sur la plateforme.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
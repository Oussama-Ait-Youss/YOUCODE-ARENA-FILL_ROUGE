<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Matchs - YouCode Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
        :root { --bg: #0b0b0e; --card: #16161a; --orange: #f97316; --gray: #9ca3af; --border: #27272a; }
        body { background: var(--bg); font-family: 'Poppins', sans-serif; color: white; padding: 2rem; margin: 0; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        .header { margin-bottom: 2rem; border-left: 4px solid var(--orange); padding-left: 1rem; }
        
        .panel { background: var(--card); padding: 2rem; border-radius: 15px; border: 1px solid var(--border); margin-bottom: 2rem; }
        .form-grid { display: grid; grid-template-columns: 1fr auto 1fr; gap: 20px; align-items: end; }
        
        select, input { width: 100%; padding: 12px; background: var(--bg); color: white; border: 1px solid var(--border); border-radius: 8px; font-family: 'Poppins'; }
        button { background: var(--orange); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%; margin-top: 15px; }
        button:hover { background: #ea580c; }

        .vs-badge { background: #27272a; padding: 10px; border-radius: 50%; font-weight: 900; color: var(--orange); text-align: center; height: fit-content; margin-bottom: 10px;}

        /* Carte de Match */
        .match-card { display: flex; justify-content: space-between; align-items: center; background: var(--bg); border: 1px solid var(--border); padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem; }
        .teams-display { display: flex; align-items: center; gap: 20px; font-size: 1.2rem; font-weight: bold; }
        .status { padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; background: rgba(249, 115, 22, 0.1); color: var(--orange); }
    </style>
</head>
<body>

<div class="container">
    <a href="#" style="color: var(--gray); text-decoration: none;">← Retour aux tournois</a>

    <div class="header">
        <h1>Planification des Matchs</h1>
        <p style="color: var(--gray);">Tournoi : <strong>{{ $tournament->title }}</strong></p>
    </div>

    @if(session('success')) <div style="color: #10b981; margin-bottom: 1rem;">{{ session('success') }}</div> @endif
    @if($errors->any()) <div style="color: #ef4444; margin-bottom: 1rem;">{{ $errors->first() }}</div> @endif

    <div class="panel">
        <h3 style="margin-top: 0;">Créer un affrontement</h3>
        <form action="{{ route('organizer.matches.store', $tournament->id) }}" method="POST">
            @csrf
            <div class="form-grid">
                <div>
                    <label style="color: var(--gray); font-size: 0.8rem;">Équipe A</label>
                    <select name="team1_id" required>
                        <option value="">Sélectionner une équipe...</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="vs-badge">VS</div>

                <div>
                    <label style="color: var(--gray); font-size: 0.8rem;">Équipe B</label>
                    <select name="team2_id" required>
                        <option value="">Sélectionner une équipe...</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="margin-top: 15px;">
                <label style="color: var(--gray); font-size: 0.8rem;">Date et Heure (Optionnel)</label>
                <input type="datetime-local" name="scheduled_at">
            </div>

            <button type="submit">Programmer le Match</button>
        </form>
    </div>

    <h3>Matchs Programmés ({{ $matches->count() }})</h3>
    @forelse($matches as $match)
        <div class="match-card">
            <div class="teams-display">
                <span>{{ $match->team1->name }}</span>
                <span style="color: var(--gray); font-size: 0.9rem; margin: 0 10px;">VS</span>
                <span>{{ $match->team2->name }}</span>
            </div>
            
            <div style="text-align: right; display: flex; align-items: center; gap: 15px;">
                @if($match->status === 'Programmé')
                    <form action="{{ route('organizer.matches.update_score', ['tournament' => $tournament->id, 'match' => $match->id]) }}" method="POST" style="display: flex; gap: 10px; align-items: center;">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="score_team1" min="0" required style="width: 60px; padding: 5px; text-align: center;" placeholder="0">
                        <span style="color: var(--gray);">-</span>
                        <input type="number" name="score_team2" min="0" required style="width: 60px; padding: 5px; text-align: center;" placeholder="0">
                        <button type="submit" style="padding: 5px 15px; margin-top: 0; background: #10b981;">Valider</button>
                    </form>
                @else
                    <div style="font-size: 1.5rem; font-weight: 900; color: var(--orange);">
                        {{ $match->score }}
                    </div>
                @endif
                
                <div class="status" style="margin-left: 10px;">{{ $match->status }}</div>
            </div>
        </div>
    @empty
        <div class="panel" style="text-align: center; color: var(--gray);">
            Aucun match n'a encore été programmé pour ce tournoi.
        </div>
    @endforelse

</div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Tournoi - YouCode Arena</title>
    <style>
        /* On garde exactement le même CSS que create.blade.php */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap');
        :root { --bg-page: #f0f0f0; --bg-white: #ffffff; --bg-input: #f2f3f5; --text-black: #111111; --text-gray: #6b7280; --accent-orange: #f97316; --border-light: #e5e7eb; }
        * { box-sizing: border-box; }
        body { background-color: var(--bg-page); font-family: 'Poppins', sans-serif; min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center; padding: 2rem; position: relative; overflow: hidden; }
        body::before { content: ''; position: fixed; top: -60px; right: -60px; width: 200px; height: 200px; background: var(--accent-orange); border-radius: 50%; opacity: 0.25; z-index: 0; }
        body::after { content: ''; position: fixed; bottom: -80px; left: -80px; width: 260px; height: 260px; background: var(--accent-orange); border-radius: 50%; opacity: 0.20; z-index: 0; }
        .auth-container { display: flex; width: 100%; max-width: 1100px; border-radius: 28px; box-shadow: 0 30px 70px -15px rgba(0, 0, 0, 0.18); overflow: hidden; position: relative; z-index: 1; }
        .auth-form-section { flex: 1.2; background-color: var(--bg-white); border-radius: 28px 0 0 28px; padding: 2.5rem 3rem 3rem 3rem; display: flex; flex-direction: column; }
        .auth-top-nav-left { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .logo-text { font-size: 1.4rem; font-weight: 900; color: var(--text-black); letter-spacing: -0.5px; }
        .back-link { font-size: 0.85rem; font-weight: 600; color: var(--text-gray); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; transition: color 0.2s ease; }
        .back-link:hover { color: var(--accent-orange); }
        .auth-header h1 { font-size: 2.4rem; font-weight: 900; color: var(--text-black); margin: 0 0 0.25rem 0; letter-spacing: -1px; line-height: 1.1; }
        .auth-header p { color: var(--text-gray); font-size: 0.85rem; margin: 0 0 1.5rem 0; font-weight: 400; }
        .form-row { display: flex; gap: 1rem; margin-bottom: 0.9rem; }
        .form-row .form-group { flex: 1; margin-bottom: 0; }
        .form-group { margin-bottom: 0.9rem; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-gray); margin-bottom: 0.3rem; padding-left: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .form-group input, .form-group select { width: 100%; background-color: var(--bg-input); border: 2px solid transparent; color: var(--text-black); padding: 0.85rem 1.4rem; border-radius: 50px; font-family: 'Poppins', sans-serif; font-size: 0.88rem; transition: all 0.25s ease; appearance: none; }
        .select-wrapper { position: relative; }
        .select-wrapper::after { content: '▼'; font-size: 0.6rem; position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); color: var(--text-gray); pointer-events: none; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: var(--accent-orange); background-color: #fff; box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.10); }
        .btn-submit { width: 100%; background-color: var(--text-black); color: var(--bg-white); border: none; padding: 1rem; border-radius: 50px; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.92rem; cursor: pointer; text-transform: uppercase; margin-top: 1rem; transition: all 0.2s ease;}
        .btn-submit:hover { background-color: #1a1a1a; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.20); }
        .alert { padding: 0.9rem 1.25rem; border-radius: 16px; margin-bottom: 1.25rem; font-size: 0.82rem; }
        .alert-error { background-color: #fee2e2; color: #991b1b; }
        .auth-graphic { flex: 0.8; background: linear-gradient(145deg, #111 0%, #333 100%); border-radius: 0 28px 28px 0; padding: 2rem; position: relative; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; } /* Fond sombre pour différencier du create */
        .graphic-content { position: relative; z-index: 10; color: white; }
        .trophy-icon { font-size: 4rem; margin-bottom: 1rem; display: inline-block; background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 50%; }
        .graphic-content h2 { font-size: 1.8rem; font-weight: 800; margin: 0 0 0.5rem 0; line-height: 1.2; }
        .graphic-content p { font-size: 0.9rem; opacity: 0.9; margin: 0 auto 2rem auto; max-width: 80%; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-top-nav-left">
                <div class="logo-text">YCA. Organizer</div>
                <a href="{{ route('organizer.tournaments.index') }}" class="back-link">
                    <span>←</span> Back to Dashboard
                </a>
            </div>
            
            <div class="auth-header">
                <h1>Update Event</h1>
                <p>Modifiez les paramètres de votre tournoi.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('organizer.tournaments.update', $tournament->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Titre du Tournoi</label>
                    <input type="text" name="title" value="{{ old('title', $tournament->title) }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Jeu Associé</label>
                        <div class="select-wrapper">
                            <select name="game_id" required>
                                @foreach($games as $game)
                                    <option value="{{ $game->id }}" {{ (old('game_id', $tournament->game_id) == $game->id) ? 'selected' : '' }}>
                                        {{ $game->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Catégorie</label>
                        <div class="select-wrapper">
                            <select name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $tournament->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Capacité Max (Joueurs)</label>
                        <input type="number" name="max_capacity" value="{{ old('max_capacity', $tournament->max_capacity) }}" required min="2">
                    </div>

                    <div class="form-group">
                        <label>Date et Heure</label>
                        <input type="datetime-local" name="event_date" value="{{ old('event_date', $tournament->event_date->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Sauvegarder les modifications</button>
            </form>
        </div>

        <div class="auth-graphic">
            <div class="graphic-content">
                <div class="trophy-icon">⚙️</div>
                <h2>Adjust the Rules</h2>
                <p>Adaptez votre événement en temps réel pour offrir la meilleure expérience à vos compétiteurs.</p>
            </div>
        </div>
    </div>
</body>
</html>
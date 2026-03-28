<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Créer un Tournoi - YouCode Arena</title>
</head>
<body>
    <h1>Créer un nouvel événement compétitif</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('organizer.tournaments.store') }}">
        @csrf

        <div>
            <label>Titre du Tournoi :</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Ex: Tournoi LoL Safi">
        </div>

        <div>
            <label>Jeu :</label>
            <select name="game_id" required>
                <option value="">-- Sélectionner un jeu --</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Catégorie :</label>
            <select name="category_id" required>
                <option value="">-- Sélectionner une catégorie --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Capacité Maximale (Quotas) :</label>
            <input type="number" name="max_capacity" value="{{ old('max_capacity') }}" required min="2">
        </div>

        <div>
            <label>Date de l'événement :</label>
            <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required>
        </div>

        <button type="submit">Ouvrir les Inscriptions</button>
    </form>
</body>
</html>
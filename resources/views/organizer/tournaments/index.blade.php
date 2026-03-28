<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Tournois - YouCode Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; background-color: #f0f0f0; padding: 2rem; margin: 0; }
        .dashboard-container { background: white; padding: 2.5rem; border-radius: 20px; max-width: 1100px; margin: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header h1 { margin: 0; font-size: 2rem; color: #111; font-weight: 800; letter-spacing: -0.5px; }
        .btn-create { background: #f97316; color: white; text-decoration: none; padding: 12px 24px; border-radius: 50px; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2); }
        .btn-create:hover { background: #ea6c05; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(249, 115, 22, 0.3); }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 16px 12px; text-align: left; border-bottom: 1px solid #f2f3f5; font-size: 0.9rem; }
        th { color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; background: #fafafa; }
        td { color: #111; font-weight: 500; }
        .status { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-block; }
        .status-avenir { background: #dbeafe; color: #1e40af; }
        .status-ouvertes { background: #d1fae5; color: #065f46; }
        .status-terminees { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #d1fae5; color: #065f46; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 500; font-size: 0.9rem; border: 1px solid #a7f3d0; }
        
        /* Actions (Edit / Delete) */
        .actions-cell { display: flex; gap: 10px; align-items: center; }
        .btn-edit { color: #3b82f6; font-weight: 600; text-decoration: none; padding: 6px 12px; border-radius: 6px; background: #eff6ff; transition: background 0.2s; }
        .btn-edit:hover { background: #dbeafe; }
        .btn-delete { color: #ef4444; font-weight: 600; background: #fef2f2; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.9rem; transition: background 0.2s; }
        .btn-delete:hover { background: #fee2e2; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        @if(session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="header">
            <h1>Mes Tournois</h1>
            <a href="{{ route('organizer.tournaments.create') }}" class="btn-create">+ Créer un Événement</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Jeu</th>
                    <th>Catégorie</th>
                    <th>Date</th>
                    <th>Capacité</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tournaments as $tournament)
                    <tr>
                        <td><strong>{{ $tournament->title }}</strong></td>
                        <td>{{ $tournament->game->name }}</td>
                        <td>{{ $tournament->category->name }}</td>
                        <td>{{ $tournament->event_date->format('d/m/Y - H:i') }}</td>
                        
                        <td>
                            <span style="font-weight: 600; color: {{ $tournament->is_full ? '#ef4444' : '#10b981' }};">
                                {{ $tournament->registered_count }}
                            </span> 
                            / {{ $tournament->max_capacity }}
                            
                            @if($tournament->is_full)
                                <br><span style="font-size: 0.7rem; color: #ef4444; font-weight: bold;">(COMPLET)</span>
                            @endif
                        </td>

                        <td>
                            <span class="status status-{{ Str::slug($tournament->status) }}">
                                {{ $tournament->status }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <a href="{{ route('organizer.tournaments.edit', $tournament->id) }}" class="btn-edit">Edit</a>
                            
                            <form action="{{ route('organizer.tournaments.destroy', $tournament->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler et supprimer ce tournoi ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #6b7280; padding: 3rem 1rem;">
                            Vous n'avez pas encore créé de tournoi. C'est le moment de lancer l'arène ! 🎮
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
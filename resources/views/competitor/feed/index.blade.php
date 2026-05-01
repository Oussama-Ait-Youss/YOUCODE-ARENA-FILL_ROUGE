<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Mur - YouCode Arena</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
        :root { --bg: #0b0b0e; --card: #16161a; --orange: #f97316; --gray: #9ca3af; --border: #27272a; --text: #f3f4f6; }

        body { background: var(--bg); font-family: 'Poppins', sans-serif; color: var(--text); margin: 0; padding: 2rem 1rem; }
        .container { max-width: 700px; margin: 0 auto; }

        /* Navigation */
        .nav-links { margin-bottom: 20px; }
        .nav-links a { color: var(--gray); text-decoration: none; margin-right: 15px; font-weight: 600; transition: color 0.2s; }
        .nav-links a:hover { color: var(--orange); }

        /* Header */
        .header { margin-bottom: 2rem; }
        .title { font-size: 2rem; font-weight: 900; color: var(--orange); margin: 0; text-transform: uppercase; letter-spacing: 1px;}
        .subtitle { color: var(--gray); font-size: 1rem; margin-top: 5px; }

        /* Formulaire de Post */
        .post-form-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 2.5rem; }
        .post-textarea { width: 100%; background: var(--bg); border: 1px solid var(--border); color: white; padding: 15px; border-radius: 8px; font-family: 'Poppins', sans-serif; resize: vertical; min-height: 80px; box-sizing: border-box; margin-bottom: 10px; font-size: 1rem; }
        .post-textarea:focus { outline: none; border-color: var(--orange); }
        .btn-submit { background: var(--orange); color: white; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s; float: right; font-family: 'Poppins', sans-serif;}
        .btn-submit:hover { background: #ea580c; }
        .clearfix::after { content: ""; clear: both; display: table; }

        /* Liste des Posts */
        .post-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; transition: border-color 0.2s; }
        .post-card:hover { border-color: #3f3f46; }
        .post-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .author-name { font-weight: 800; color: white; display: flex; align-items: center; gap: 10px; }
        .author-badge { background: rgba(249, 115, 22, 0.15); color: var(--orange); padding: 3px 10px; border-radius: 12px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; }
        .post-time { color: var(--gray); font-size: 0.8rem; }
        .post-content { font-size: 1.05rem; line-height: 1.6; color: #e5e7eb; margin-bottom: 15px; white-space: pre-wrap;}
        
        /* Badges de contexte (Match/Challenge) */
        .context-badge { display: inline-block; background: #1e1e24; border: 1px solid #3f3f46; color: var(--gray); font-size: 0.8rem; padding: 5px 12px; border-radius: 6px; margin-bottom: 15px; font-weight: 600;}

        /* Section Commentaires */
        .comments-section { border-top: 1px solid var(--border); padding-top: 15px; margin-top: 15px; }
        .comment { background: rgba(0,0,0,0.3); padding: 12px 15px; border-radius: 8px; margin-bottom: 10px; border-left: 2px solid #3f3f46;}
        .comment-author { font-weight: 700; font-size: 0.85rem; color: var(--orange); margin-bottom: 4px; }
        .comment-content { font-size: 0.9rem; color: #d1d5db; }
        
        /* Messages d'alerte */
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;}
        .empty-state { text-align: center; color: var(--gray); padding: 3rem; border: 1px dashed var(--border); border-radius: 12px; }
    </style>
</head>
<body>

<div class="container">
    <div class="nav-links">
        <a href="{{ route('dashboard') }}">← Retour au QG</a>
        <a href="{{ route('competitor.tournaments.index') }}">🎮 Tournois</a>
    </div>

    <div class="header">
        <h1 class="title"> Le Mur de l'Arène</h1>
        <p class="subtitle">Annonces, trash-talk et discussions entre compétiteurs.</p>
    </div>

    @if(session('success'))
        <div class="alert-success">
             {{ session('success') }}
        </div>
    @endif

    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Organisateur'))
        <div class="post-form-card">
            <form action="{{ route('competitor.feed.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <textarea name="content" class="post-textarea" placeholder="Que veux-tu dire à l'arène, {{ auth()->user()->username }} ?" required></textarea>
                <select name="game_id" class="post-textarea" style="min-height: auto;">
                    <option value="">Tous les jeux</option>
                    @foreach($games as $game)
                        <option value="{{ $game->id }}">{{ $game->name }}</option>
                    @endforeach
                </select>
                <input type="file" name="image" accept="image/*" style="margin-bottom: 10px;">
                <div class="clearfix">
                    <button type="submit" class="btn-submit">Publier</button>
                </div>
            </form>
        </div>
    @else
        <div class="post-form-card" style="color: var(--gray);">
            Les compétiteurs peuvent commenter les annonces, mais seuls les organisateurs et les admins peuvent publier ici.
        </div>
    @endif

    <div class="feed">
        @forelse($posts as $post)
            <div class="post-card">
                <div class="post-header">
                    <div class="author-name">
                        {{ $post->author->username }}
                        @if($post->author->id === auth()->id())
                            <span class="author-badge">C'est toi</span>
                        @endif
                    </div>
                    <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                </div>

                @if($post->game)
                    <div class="context-badge"> Jeu : {{ $post->game->name }}</div>
                @endif

                @if($post->match_id)
                    <div class="context-badge"> Lié à un Match</div>
                @endif

                <div class="post-content">{{ $post->content }}</div>

                @if($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" style="width:100%; max-height: 360px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
                @endif

                @if($post->comments->count() > 0)
                    <div class="comments-section">
                        <p style="font-size: 0.8rem; color: var(--gray); margin-top: 0; margin-bottom: 10px; text-transform: uppercase;">Commentaires ({{ $post->comments->count() }})</p>
                        @foreach($post->comments as $comment)
                            <div class="comment">
                                <div class="comment-author">{{ $comment->author->username ?? 'Utilisateur supprimé' }}</div>
                                <div class="comment-content">{{ $comment->content }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <div style="font-size: 2rem; margin-bottom: 10px;"></div>
                Le mur est bien silencieux...<br>Sois le premier à lancer les hostilités !
            </div>
        @endforelse
    </div>
</div>

</body>
</html>

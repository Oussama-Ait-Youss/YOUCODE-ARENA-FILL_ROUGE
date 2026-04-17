<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isDuo ? 'Créer une Équipe' : 'Rejoindre le Tournoi' }} - {{ $tournament->title }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#050505',
                        crimson: '#DC143C',
                        cyan: '#00F0FF',
                        gold: '#FFD700',
                    },
                    fontFamily: {
                        display: ['Teko', 'sans-serif'],
                        sans: ['Outfit', 'sans-serif']
                    },
                    boxShadow: {
                        'neon': '0 0 20px rgba(220, 20, 60, 0.5)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #050505;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(220, 20, 60, 0.08) 0%, transparent 25%), 
                radial-gradient(circle at 85% 30%, rgba(0, 240, 255, 0.05) 0%, transparent 25%);
        }

        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.7), rgba(10, 12, 18, 0.8));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="text-gray-200 font-sans min-h-screen relative selection:bg-crimson selection:text-white flex items-center justify-center p-4">

    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>

    <div class="glass-card relative z-10 w-full max-w-lg p-8 sm:p-12 rounded-2xl shadow-2xl border-t border-t-crimson/50">
        
        <div class="text-center mb-8">
            <h1 class="font-display font-bold text-4xl text-white tracking-wider mb-2">
                {{ $isDuo ? 'NOUVELLE ÉQUIPE' : "ENTRER DANS L'ARÈNE" }}
            </h1>
            
            <div class="inline-block bg-gold/10 text-gold border border-gold/20 px-4 py-1 rounded-full text-sm font-bold tracking-widest uppercase">
                🏆 {{ $tournament->title }}
            </div>
            
            <span class="block text-cyan font-bold tracking-widest text-sm uppercase mt-4">
                Format : {{ $isDuo ? '👥 DUO (2 joueurs)' : '👤 SOLO' }}
            </span>
        </div>

        <form action="{{ route('competitor.teams.store', $tournament->id) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="name" class="block text-gray-400 text-sm font-bold tracking-wider uppercase mb-2">
                    {{ $isDuo ? "Nom de l'équipe" : 'Pseudo affiché dans le tournoi' }}
                </label>
                <input type="text" name="name" id="name" required 
                       class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-crimson focus:ring-1 focus:ring-crimson transition-colors font-sans"
                       placeholder="{{ $isDuo ? 'Ex: Titans de YouCode' : 'Ex: Oussama_Pro' }}" autofocus>
            </div>

            @if($isDuo)
                <div class="mb-6">
                    <label for="partner_email" class="block text-gray-400 text-sm font-bold tracking-wider uppercase mb-2">
                        Email du coéquipier (optionnel)
                    </label>
                    <input type="email" name="partner_email" id="partner_email" 
                           class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition-colors font-sans"
                           placeholder="joueur2@youcode.ma">
                    <span class="block text-xs text-gray-500 mt-2 font-medium">
                        * Une invitation sera envoyée à ce joueur pour rejoindre votre équipe.
                    </span>
                </div>
            @else
                <p class="block text-xs text-gray-500 mt-[-10px] mb-6 font-medium">
                    Tournoi solo : tu seras inscrit individuellement et ce nom sera utilisé dans l'arbre.
                </p>
            @endif

            <button type="submit" class="w-full bg-crimson hover:bg-red-700 text-white font-display font-bold text-2xl tracking-widest uppercase py-3 rounded-lg transition shadow-neon mt-2">
                {{ $isDuo ? "Valider l'équipe" : 'Valider mon inscription' }}
            </button>
        </form>

        <a href="{{ route('competitor.tournaments.show', $tournament) }}" class="block text-center mt-6 text-gray-500 hover:text-white transition font-bold tracking-wider text-sm uppercase">
            ← Annuler et revenir
        </a>
    </div>

</body>
</html>
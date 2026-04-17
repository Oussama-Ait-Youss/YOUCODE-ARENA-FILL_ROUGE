<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Tournoi - YouCode Arena</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#050505',
                        'bg-soft': '#0B0F19',
                        crimson: '#DC143C',
                        cyan: '#00F0FF',
                        gold: '#FFD700',
                    },
                    fontFamily: {
                        display: ['Teko', 'sans-serif'],
                        sans: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #050505;
            background-image: radial-gradient(circle at 50% 10%, rgba(0, 240, 255, 0.05) 0%, transparent 40%);
        }
        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.8), rgba(10, 12, 18, 0.9));
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
    </style>
</head>
<body class="text-gray-200 font-sans min-h-screen relative flex items-center justify-center p-6 selection:bg-cyan selection:text-black">

    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>

    <div class="glass-card w-full max-w-5xl rounded-2xl overflow-hidden flex flex-col md:flex-row relative z-10 shadow-[0_0_50px_rgba(0,0,0,0.5)]">
        
        <div class="w-full md:w-3/5 p-8 md:p-12">
            
            <div class="flex justify-between items-center mb-8">
                <div class="font-display font-bold text-2xl tracking-widest text-white">YCA. <span class="text-cyan">ORGANIZER</span></div>
                <a href="{{ route('organizer.dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-cyan transition flex items-center gap-2">
                    <span>←</span> Retour au Dashboard
                </a>
            </div>
            
            <div class="mb-8">
                <h1 class="text-5xl font-display font-bold text-white uppercase tracking-wider mb-2">Update Event</h1>
                <p class="text-gray-400">Modifiez les paramètres de votre tournoi en temps réel.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('organizer.tournaments.update', $tournament->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                @if(auth()->user()->hasRole('Admin'))
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Organisateur assigné</label>
                        <select name="organizer_id" class="w-full bg-[#0B0F19] border border-white/10 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition appearance-none">
                            <option value="">Moi-même</option>
                            @foreach($organizers as $organizer)
                                <option value="{{ $organizer->id }}" {{ (int) old('organizer_id', $tournament->organizer_id) === $organizer->id ? 'selected' : '' }}>
                                    {{ $organizer->username }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Titre du Tournoi</label>
                    <input type="text" name="title" value="{{ old('title', $tournament->title) }}" required 
                           class="w-full bg-[#0B0F19] border border-white/10 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Jeu Associé</label>
                        <select name="game_id" required class="w-full bg-[#0B0F19] border border-white/10 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition appearance-none">
                            @foreach($games as $game)
                                <option value="{{ $game->id }}" {{ (old('game_id', $tournament->game_id) == $game->id) ? 'selected' : '' }}>
                                    {{ $game->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Catégorie</label>
                        <select name="category_id" required class="w-full bg-[#0B0F19] border border-white/10 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition appearance-none">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('category_id', $tournament->category_id) == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Capacité Max (Joueurs)</label>
                        <input type="number" name="max_capacity" value="{{ old('max_capacity', $tournament->max_capacity) }}" required min="2"
                               class="w-full bg-[#0B0F19] border border-white/10 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Date et Heure</label>
                        <input type="datetime-local" name="event_date" value="{{ old('event_date', $tournament->event_date->format('Y-m-d\TH:i')) }}" required
                               class="w-full bg-[#0B0F19] border border-white/10 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition">
                    </div>
                </div>

                <button type="submit" class="w-full bg-cyan hover:bg-[#00d0dd] text-black font-display font-bold text-xl tracking-widest uppercase py-3 rounded-lg transition shadow-[0_0_15px_rgba(0,240,255,0.3)] mt-4">
                    Sauvegarder les modifications
                </button>
            </form>
        </div>

        <div class="hidden md:flex md:w-2/5 bg-gradient-to-br from-[#002f33] to-black border-l border-white/5 p-12 flex-col justify-center items-center text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-cyan/5 animate-pulse"></div>
            
            <div class="relative z-10">
                <div class="text-7xl mb-6 drop-shadow-[0_0_20px_rgba(0,240,255,0.5)]">⚙️</div>
                <h2 class="text-4xl font-display font-bold text-white uppercase tracking-wider mb-4">Adjust the Rules</h2>
                <p class="text-gray-400 text-sm leading-relaxed mb-8">Adaptez votre événement en temps réel pour offrir la meilleure expérience possible à vos compétiteurs.</p>
                
                <div class="bg-white/5 border border-white/10 p-4 rounded-lg">
                    <p class="text-cyan font-bold text-sm mb-1">Attention</p>
                    <p class="text-gray-500 text-xs">Modifier la capacité d'un tournoi en cours peut affecter les joueurs sur liste d'attente.</p>
                </div>
            </div>
        </div>

    </div>
</body>
</html>

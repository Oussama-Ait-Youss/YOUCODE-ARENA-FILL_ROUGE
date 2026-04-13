<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouCode Arena | La plateforme de compétition ultime</title>

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
                radial-gradient(circle at 50% 50%, rgba(220, 20, 60, 0.15) 0%, transparent 50%), 
                radial-gradient(circle at 85% 30%, rgba(0, 240, 255, 0.05) 0%, transparent 25%);
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #DC143C; }

        .glass {
            background: rgba(15, 20, 30, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.7), rgba(10, 12, 18, 0.8));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(220, 20, 60, 0.4);
            transform: translateY(-10px);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5), 0 0 20px rgba(220, 20, 60, 0.2);
        }
    </style>
</head>

<body class="text-gray-200 font-sans min-h-screen relative selection:bg-crimson selection:text-white overflow-x-hidden flex flex-col">

    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>

    <nav class="fixed top-0 w-full z-50 glass border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-crimson flex items-center justify-center transform skew-x-[-10deg]">
                    <span class="font-display font-bold text-black text-2xl transform skew-x-[10deg]">Y</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-display font-bold tracking-widest leading-none text-white group-hover:text-crimson transition-colors">YOUCODE</span>
                    <span class="text-xs font-display tracking-[0.3em] text-gray-500 group-hover:text-white transition-colors">ARENA</span>
                </div>
            </a>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-white font-display tracking-widest text-lg bg-crimson hover:bg-red-700 px-6 py-2 rounded shadow-neon transition">MON DASHBOARD</a>
                @else
                    <a href="{{ route('login') }}" class="hidden md:block text-gray-400 hover:text-white font-bold text-sm tracking-widest uppercase transition">Connexion</a>
                    <a href="{{ route('register') }}" class="text-white font-display tracking-widest text-lg bg-crimson hover:bg-red-700 px-6 py-2 rounded shadow-neon transition">REJOINDRE L'ARÈNE</a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="relative z-10 flex flex-col justify-center items-center text-center min-h-screen px-6 pt-20">
        <div class="absolute inset-0 bg-crimson/5 animate-pulse rounded-full blur-[150px] max-w-lg mx-auto h-96 -z-10"></div>
        
        <h1 class="text-7xl md:text-9xl font-display font-bold text-white tracking-wider leading-none mb-6">
            DOMINE <br> <span class="text-crimson text-shadow-neon">L'ARÈNE</span>
        </h1>
        <p class="text-xl text-gray-400 max-w-2xl mb-10 font-light">
            Inscris-toi aux tournois les plus épiques de YouCode. Sport, E-Sport ou Code, prouve ta valeur et grimpe au sommet du classement.
        </p>
        
        <div class="flex flex-wrap justify-center gap-6">
            @auth
                <a href="{{ route('competitor.tournaments.index') }}" class="bg-crimson hover:bg-red-700 text-white font-display tracking-widest text-xl px-10 py-4 rounded shadow-neon transition transform hover:scale-105">VOIR LES TOURNOIS</a>
            @else
                <a href="{{ route('register') }}" class="bg-crimson hover:bg-red-700 text-white font-display tracking-widest text-xl px-10 py-4 rounded shadow-neon transition transform hover:scale-105">COMMENCER MAINTENANT</a>
                <a href="{{ route('login') }}" class="bg-white/5 hover:bg-white/10 text-white border border-white/20 font-display tracking-widest text-xl px-10 py-4 rounded transition">SE CONNECTER</a>
            @endauth
        </div>
    </section>

    <section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass-card p-10 rounded-2xl border-t-4 border-t-crimson">
                <div class="text-5xl mb-6">🎮</div>
                <h3 class="font-display font-bold text-3xl text-white tracking-wider mb-4">MULTI-GAMING</h3>
                <p class="text-gray-400">De League of Legends aux tournois de Babyfoot, trouvez la compétition qui vous correspond et affrontez vos camarades.</p>
            </div>
            
            <div class="glass-card p-10 rounded-2xl border-t-4 border-t-gold relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gold/10 blur-[50px]"></div>
                <div class="text-5xl mb-6">👑</div>
                <h3 class="font-display font-bold text-3xl text-white tracking-wider mb-4">LEADERBOARD</h3>
                <p class="text-gray-400">Suivez vos statistiques de victoires et de défaites, gagnez des matchs et devenez le joueur numéro 1 de YouCode Arena.</p>
            </div>
            
            <div class="glass-card p-10 rounded-2xl border-t-4 border-t-cyan">
                <div class="text-5xl mb-6">💬</div>
                <h3 class="font-display font-bold text-3xl text-white tracking-wider mb-4">HUB COMMUNAUTAIRE</h3>
                <p class="text-gray-400">Échangez avec vos adversaires sur le mur, lancez des défis de practice et partagez vos moments de gloire sur le fil d'actualité.</p>
            </div>
        </div>
    </section>

    <footer class="relative z-10 glass border-t border-white/5 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center text-xs text-gray-500 font-bold tracking-widest uppercase">
            &copy; 2026 YouCode Arena - Développé par Oussama Ait Youss
        </div>
    </footer>

</body>
</html>
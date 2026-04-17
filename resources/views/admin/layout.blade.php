<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') - YouCode Arena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#050505',
                        panel: '#0f1015',
                        crimson: '#DC143C',
                        cyan: '#00F0FF',
                        gold: '#FFD700',
                        success: '#22C55E',
                        warning: '#f59e0b'
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
            background-image: radial-gradient(circle at 50% 0%, rgba(220, 20, 60, 0.05) 0%, transparent 50%);
        }
        .glass-card {
            background: #0f1015;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 18px;
        }
        ::-webkit-scrollbar { height: 6px; width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #DC143C; }
    </style>
</head>
<body class="text-gray-200 font-sans min-h-screen flex">
    <aside class="hidden lg:flex w-72 shrink-0 flex-col border-r border-white/5 bg-[#050505]/95">
        <div class="h-20 border-b border-white/5 flex items-center px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-crimson flex items-center justify-center transform skew-x-[-10deg]">
                    <span class="font-display font-bold text-black text-2xl transform skew-x-[10deg]">Y</span>
                </div>
                <div>
                    <div class="text-2xl font-display font-bold tracking-widest text-white">YOUCODE</div>
                    <div class="text-xs tracking-[0.3em] text-gray-500">ADMIN ARENA</div>
                </div>
            </a>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            @php $activeTab = trim($__env->yieldContent('active-tab')) ?: 'dashboard'; @endphp
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold transition {{ $activeTab === 'dashboard' ? 'bg-white/10 text-white border-l-2 border-cyan' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }}">
                <span>📊</span> Vue globale
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold transition {{ $activeTab === 'users' ? 'bg-white/10 text-white border-l-2 border-cyan' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }}">
                <span>👥</span> Utilisateurs
            </a>
            <a href="{{ route('admin.tournaments.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold transition {{ $activeTab === 'tournaments' ? 'bg-white/10 text-white border-l-2 border-cyan' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-2 border-transparent' }}">
                <span>🏆</span> Tournois
            </a>
        </nav>

        <div class="p-4 border-t border-white/5 space-y-3">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 rounded-xl border border-white/10 px-4 py-3 text-sm font-bold text-gray-300 hover:bg-white/5 hover:text-white transition">
                Retour au hub
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-crimson/30 px-4 py-3 text-sm font-bold text-crimson hover:bg-crimson hover:text-white transition">
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 min-w-0">
        <header class="border-b border-white/5 bg-[#050505]/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-6 py-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-gray-500">@yield('eyebrow', 'Administration')</p>
                    <h1 class="text-4xl font-display font-bold tracking-wider text-white">@yield('page-title', 'Centre de commandement')</h1>
                    <p class="text-sm text-gray-400 mt-1">@yield('page-description')</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block text-right">
                        <div class="text-sm font-bold text-white">{{ auth()->user()->username }}</div>
                        <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Admin</div>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=111827&color=fff" class="w-11 h-11 rounded-full border border-white/10" alt="{{ auth()->user()->username }}">
                </div>
            </div>
            <div class="lg:hidden border-t border-white/5">
                @php $activeTab = trim($__env->yieldContent('active-tab')) ?: 'dashboard'; @endphp
                <div class="px-6 py-3 flex flex-wrap gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="rounded-xl px-4 py-2 text-sm font-bold transition {{ $activeTab === 'dashboard' ? 'bg-white/10 text-white border border-cyan/30' : 'border border-white/10 text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Vue globale
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="rounded-xl px-4 py-2 text-sm font-bold transition {{ $activeTab === 'users' ? 'bg-white/10 text-white border border-cyan/30' : 'border border-white/10 text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Utilisateurs
                    </a>
                    <a href="{{ route('admin.tournaments.index') }}" class="rounded-xl px-4 py-2 text-sm font-bold transition {{ $activeTab === 'tournaments' ? 'bg-white/10 text-white border border-cyan/30' : 'border border-white/10 text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Tournois
                    </a>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-6 py-8">
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-success/20 bg-success/10 px-4 py-3 text-success font-bold">✅ {{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl border border-crimson/20 bg-crimson/10 px-4 py-3 text-crimson font-bold">⛔ {{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-crimson/20 bg-crimson/10 px-4 py-3 text-crimson font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>

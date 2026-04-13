<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God Mode - Utilisateurs</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#050505',
                        'bg-soft': '#0f1015',
                        crimson: '#DC143C',
                        cyan: '#00F0FF',
                        gold: '#FFD700',
                        success: '#22C55E'
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
            border-radius: 16px;
        }

        ::-webkit-scrollbar { height: 6px; width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #DC143C; }
    </style>
</head>

<body class="text-gray-200 font-sans min-h-screen relative selection:bg-crimson selection:text-white flex flex-col">

    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>

    <nav class="fixed top-0 w-full z-50 bg-[#050505]/80 backdrop-blur-md border-b border-white/5">
        <div class="max-w-[1400px] mx-auto px-6 h-16 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 bg-crimson flex items-center justify-center transform skew-x-[-10deg]">
                    <span class="font-display font-bold text-black text-xl transform skew-x-[10deg]">Y</span>
                </div>
                <span class="text-xl font-display font-bold tracking-widest text-white group-hover:text-crimson transition-colors">ARENA</span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">HUB</a>
                <a href="{{ route('competitor.tournaments.index') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">TOURNAMENTS</a>
                
                <a href="{{ route('admin.dashboard') }}" class="text-white font-display tracking-wider text-lg relative">
                    ADMINISTRATION
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-crimson shadow-[0_0_10px_rgba(220,20,60,0.5)]"></span>
                </a>
            </div>

            <div class="flex items-center gap-3 cursor-pointer group" onclick="document.getElementById('logout-form').submit();">
                <div class="w-8 h-8 rounded-full border border-white/20 relative">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=transparent&color=fff" class="w-full h-full object-cover rounded-full">
                </div>
                <span class="text-gray-400 text-xs tracking-widest uppercase hover:text-crimson">Sortir</span>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-[1400px] mx-auto w-full p-6 pt-24">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-display font-bold text-white tracking-wider">👥 BASE DE DONNÉES JOUEURS</h1>
                <p class="text-gray-400 text-sm mt-1">Gérez les rôles, promouvez des organisateurs ou bannissez les éléments perturbateurs.</p>
            </div>
            
            <div class="flex bg-[#0f1015] border border-white/10 rounded-lg p-1">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm text-gray-400 hover:text-white transition rounded-md">Vue d'ensemble</a>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm bg-white/10 text-white font-bold rounded-md shadow">Utilisateurs</a>
                <a href="{{ route('admin.tournaments.index') }}" class="px-4 py-2 text-sm text-gray-400 hover:text-white transition rounded-md">Tournois</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-success/10 border border-success/20 text-success p-4 rounded-xl font-bold mb-6 flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="glass-card p-6 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-white/10">
                        <th class="pb-4 font-medium pl-2">Pseudo</th>
                        <th class="pb-4 font-medium">Email</th>
                        <th class="pb-4 font-medium">Rôle Actuel</th>
                        <th class="pb-4 font-medium">Attribution de Rôle</th>
                        <th class="pb-4 font-medium text-right pr-2">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($users as $user)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition group">
                        <td class="py-4 pl-2 font-bold text-white flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=27272a&color=fff" class="w-8 h-8 rounded-full">
                            {{ $user->username }}
                        </td>
                        
                        <td class="py-4 text-gray-400">{{ $user->email }}</td>
                        
                        <td class="py-4">
                            @php $roleName = $user->roles->first()->name ?? 'Compétiteur'; @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full 
                                {{ $roleName == 'Admin' ? 'bg-cyan/10 text-cyan' : ($roleName == 'Organisateur' ? 'bg-gold/10 text-gold' : 'bg-gray-800 text-gray-300') }}">
                                {{ $roleName }}
                            </span>
                        </td>
                        
                        <td class="py-4">
                            <form action="{{ route('admin.users.change_role', $user->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="bg-black/50 border border-white/10 text-white text-xs rounded px-2 py-1.5 focus:border-cyan outline-none cursor-pointer">
                                    <option value="Compétiteur" {{ $roleName == 'Compétiteur' ? 'selected' : '' }}>Compétiteur</option>
                                    <option value="Organisateur" {{ $roleName == 'Organisateur' ? 'selected' : '' }}>Organisateur</option>
                                    <option value="Jury" {{ $roleName == 'Jury' ? 'selected' : '' }}>Jury</option>
                                </select>
                                <button type="submit" class="bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 hover:text-white px-3 py-1.5 rounded text-xs transition">
                                    Sauver
                                </button>
                            </form>
                        </td>
                        
                        <td class="py-4 text-right pr-2">
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Es-tu sûr de vouloir bannir {{ $user->username }} définitivement ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border border-crimson/50 text-crimson hover:bg-crimson hover:text-white px-3 py-1.5 rounded text-xs font-bold transition">
                                    Bannir 🔨
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>
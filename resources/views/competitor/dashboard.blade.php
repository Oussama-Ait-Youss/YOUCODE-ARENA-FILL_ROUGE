<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouCode Arena - Competition Hub</title>

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
                        panel: 'rgba(20, 25, 40, 0.6)',
                        border: 'rgba(255, 255, 255, 0.1)',
                        crimson: '#DC143C',
                        cyan: '#00F0FF',
                        gold: '#FFD700',
                        violet: '#8B5CF6',
                        success: '#22C55E'
                    },
                    fontFamily: {
                        display: ['Teko', 'sans-serif'],
                        sans: ['Outfit', 'sans-serif']
                    },
                    backgroundImage: {
                        'grid-pattern': "url('https://www.transparenttextures.com/patterns/carbon-fibre.png')",
                    },
                    boxShadow: {
                        'neon': '0 0 20px rgba(220, 20, 60, 0.5)',
                        'cyan-glow': '0 0 15px rgba(0, 240, 255, 0.4)',
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
            transform: translateY(-5px);
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

            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-white font-display tracking-wider text-lg relative">
                    COMPETITION HUB
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-crimson shadow-neon"></span>
                </a>
                <a href="{{ route('competitor.tournaments.index') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">TOURNAMENTS</a>
                <a href="{{ route('competitor.profile') }}" class="text-gray-400 hover:text-white font-display tracking-wider text-lg transition-colors">MON PROFIL</a>

                @if(auth()->user()->hasRole('Organisateur'))
                    <a href="{{ route('organizer.dashboard') }}" class="text-gold hover:text-white font-display tracking-wider text-lg transition-colors flex items-center gap-1"> MES TOURNOIS</a>
                @endif

                @if(auth()->user()->hasRole('Admin'))
                    <a href="{{ route('admin.dashboard') }}" class="text-cyan hover:text-white font-display tracking-wider text-lg transition-colors flex items-center gap-1"> ADMINISTRATION</a>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 cursor-pointer group" onclick="document.getElementById('logout-form').submit();">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-crimson to-violet border-2 border-white/20 relative group-hover:scale-105 transition">
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-success rounded-full border-2 border-gray-900"></span>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=transparent&color=fff" class="w-full h-full object-cover rounded-full">
                    </div>
                    <span class="text-gray-400 group-hover:text-crimson font-bold text-sm tracking-widest uppercase transition-colors hidden sm:block">Déconnexion</span>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </div>
        </div>
    </nav>

    <main class="relative z-10 flex-grow max-w-7xl mx-auto w-full p-6 pt-24 grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <aside class="hidden md:block col-span-1">
            <div class="glass-card rounded-xl p-4 sticky top-24">
                <h3 class="font-display font-bold text-gray-400 uppercase text-lg tracking-wider mb-4 border-b border-white/10 pb-2">Categories</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 p-3 rounded transition font-display tracking-wide text-lg {{ !request('category') ? 'bg-white/5 text-crimson border-l-4 border-crimson' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                             Toutes
                        </a>
                    </li>
                    @foreach ($categories as $cat)
                        @php 
                            $isActive = request('category') == $cat->id;
                            $activeClass = $isActive ? 'bg-white/5 text-crimson border-l-4 border-crimson' : 'text-gray-400 hover:text-white hover:bg-white/5'; 
                        @endphp
                        <li class="rounded cursor-pointer transition font-display tracking-wide text-lg">
                            <a href="?category={{ $cat->id }}" class="flex items-center gap-2 p-3 {{ $activeClass }}">
                                 {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <section class="col-span-1 md:col-span-2 space-y-6">
            
            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Organisateur'))
                <div class="glass-card rounded-xl p-6 mb-8 border border-crimson/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-crimson/5 animate-pulse"></div>
                    
                    <form action="{{ route('competitor.feed.store') }}" method="POST" enctype="multipart/form-data" class="relative z-10">
                        @csrf
                        <textarea name="content" rows="3" class="w-full bg-black/80 border border-white/10 rounded-lg p-4 text-white placeholder-gray-500 focus:outline-none focus:border-crimson transition-colors resize-none" placeholder="Que veux-tu partager avec l'Arène, {{ auth()->user()->username }} ?" required></textarea>
                        
                        <div class="mt-4 relative inline-block min-w-[250px]">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-crimson">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            </div>
                            
                            <select name="category_id" class="w-full appearance-none bg-black/60 border border-white/10 hover:border-white/20 rounded-lg pl-9 pr-10 py-2.5 text-sm font-bold tracking-wider uppercase text-gray-300 focus:outline-none focus:border-crimson transition-all cursor-pointer">
                                <option value="" class="bg-[#0B0F19]"> Général (Toutes)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" class="bg-[#0B0F19]">
                                         {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mt-4">
                            <label class="cursor-pointer flex items-center gap-2 text-gray-400 hover:text-cyan transition bg-white/5 hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-bold border border-white/5">
                                <span>📷 Ajouter une image</span>
                                <input type="file" name="image" class="hidden" accept="image/*" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                            </label>
                            <span id="file-name" class="text-xs text-cyan truncate max-w-[150px] ml-2"></span>
                            <button type="submit" class="bg-crimson hover:bg-red-700 text-white font-display tracking-widest px-8 py-2 rounded transition-colors shadow-neon">PUBLIER</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="glass-card rounded-xl p-6 mb-8 text-center border-t-2 border-t-cyan">
                    <h3 class="font-display font-bold text-xl text-white tracking-wider">🔥 L'ARÈNE EST OUVERTE</h3>
                    <p class="text-gray-400 text-sm mt-1">Suis les annonces officielles ici.</p>
                </div>
            @endif

            @forelse ($posts as $post)
                <div class="glass-card rounded-xl p-6 group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->username ?? 'Inconnu') }}&background=transparent&color=fff" class="rounded-full w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-display font-bold text-xl text-white">
                                {{ $post->author->username ?? 'Utilisateur' }}
                                @if($post->author_id === auth()->id()) <span class="text-xs ml-2 text-gold">(Moi)</span> @endif
                            </div>
                            <div class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                                {{ $post->created_at->diffForHumans() }} 
                                @if($post->category) • {{ $post->category->name }}</span> @endif
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-300 text-base mb-4 leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>

                    @if($post->image_path)
                        <div class="mb-4 rounded-lg overflow-hidden border border-white/10">
                            <img src="{{ asset('storage/' . $post->image_path) }}" class="w-full h-auto object-cover max-h-96">
                        </div>
                    @endif

                    <div class="flex gap-6 border-t border-white/5 pt-4 text-xs text-gray-500 font-bold uppercase tracking-wider">
                        <span class="hover:text-crimson cursor-pointer transition flex items-center gap-1">💬 {{ $post->comments->count() }} Commentaires</span>
                    </div>

                    @foreach($post->comments as $comment)
                        <div class="bg-black/30 p-3 rounded text-sm mt-2">
                            <span class="text-crimson font-bold">{{ $comment->author->username ?? 'Inconnu' }} :</span>
                            <span class="text-gray-400">{{ $comment->content }}</span>
                        </div>
                    @endforeach

                    <form action="{{ route('competitor.comments.store', $post->id) }}" method="POST" class="mt-4 flex gap-3">
                        @csrf
                        <input type="text" name="content" placeholder="Répondre..." class="flex-grow bg-black/50 border border-white/10 rounded-full px-4 text-sm text-white focus:outline-none focus:border-cyan transition-colors" required>
                        <button type="submit" class="text-cyan hover:text-white font-bold text-sm px-3">Envoyer</button>
                    </form>
                </div>
            @empty
                <div class="glass-card rounded-xl p-12 text-center text-gray-500">
                    <div class="text-4xl mb-4"> </div>
                    <div class="font-display font-bold text-xl">L'arène est silencieuse...</div>
                </div>
            @endforelse
        </section>

        <aside class="hidden md:block col-span-1">
            <div class="glass-card border border-gold/30 rounded-xl p-6 text-center sticky top-24 overflow-hidden">
                <div class="absolute inset-0 bg-gold/5 animate-pulse"></div>
                <div class="text-gold text-4xl mb-2">👑</div>
                <h3 class="font-display font-bold text-2xl text-white mb-4">LAST WINNER</h3>
                <div class="w-20 h-20 bg-gray-700 rounded-full mx-auto my-4 border-2 border-gold p-1 relative z-10">
                    <img src="https://ui-avatars.com/api/?name=Oussama+Pro&background=000&color=FFD700" class="w-full h-full rounded-full object-cover">
                </div>
                <div class="font-display font-bold text-xl text-gold tracking-wider">Oussama_Pro</div>
                <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">YouCode Hackathon</div>
            </div>
        </aside>

    </main>

    <footer class="glass border-t border-white/5 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center text-xs text-gray-500">
            <p>© 2026 YouCode Arena. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
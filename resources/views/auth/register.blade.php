<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - YouCode Arena</title>

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
        .glass-card {
            background: linear-gradient(145deg, rgba(20, 25, 35, 0.7), rgba(10, 12, 18, 0.8));
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="text-gray-200 font-sans min-h-screen relative selection:bg-cyan selection:text-black flex items-center justify-center p-4 md:p-8">

    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>

    <div class="glass-card relative z-10 w-full max-w-5xl rounded-2xl shadow-2xl flex flex-col md:flex-row overflow-hidden border-t-2 border-t-cyan">
        
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative z-20 bg-black/20">
            
            <div class="flex justify-between items-center mb-6">
                <div class="font-display font-bold text-3xl text-white tracking-widest flex items-center gap-1">
                    <span class="text-cyan">Y</span>CA.
                </div>
                <div class="bg-black/50 border border-white/10 px-3 py-1 rounded-full text-xs font-bold text-gray-400 flex items-center gap-2 cursor-pointer hover:text-white transition">
                    <span>🇲🇦</span> EN <span class="text-[10px]">▼</span>
                </div>
            </div>

            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-full bg-cyan text-black font-bold flex items-center justify-center text-sm shadow-cyan-glow">1</div>
                <div class="flex-1 h-1 bg-cyan rounded-full shadow-cyan-glow"></div>
                <div class="w-8 h-8 rounded-full bg-cyan text-black font-bold flex items-center justify-center text-sm shadow-cyan-glow">2</div>
                <div class="flex-1 h-1 bg-white/10 rounded-full"></div>
                <div class="w-8 h-8 rounded-full bg-black border border-white/20 text-gray-500 font-bold flex items-center justify-center text-sm">3</div>
            </div>

            <div class="mb-6">
                <h1 class="font-display font-bold text-4xl text-white tracking-wider uppercase mb-1">Join the Arena</h1>
                <p class="text-gray-400 text-sm font-medium">Create your competitor profile today.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
                @csrf 
                <div>
                    <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Your gamertag / username" 
                           class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition-colors font-sans">
                </div>
                <div>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Your email address" 
                           class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition-colors font-sans">
                </div>
                <div>
                    <input type="password" name="password" required placeholder="Create password" 
                           class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition-colors font-sans">
                </div>
                <div>
                    <input type="password" name="password_confirmation" required placeholder="Confirm password" 
                           class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan transition-colors font-sans">
                </div>
                
                <button type="submit" class="w-full bg-cyan hover:bg-[#00d7e6] text-black font-display font-bold text-2xl tracking-widest uppercase py-3 rounded-lg transition shadow-cyan-glow mt-2">
                    Sign up
                </button>
            </form>

            <div class="text-center mt-4 text-xs text-gray-500">
                By signing up you agree to our <a href="#" class="text-cyan hover:underline">Terms of Service</a> and <a href="#" class="text-cyan hover:underline">Privacy Policy</a>
            </div>

            <div class="text-center mt-6 text-sm text-gray-400">
                Already have an account? <a href="{{ route('login') }}" class="text-white font-bold hover:text-cyan transition">Log in</a>
            </div>
        </div>

        <div class="hidden md:flex w-1/2 relative flex-col items-center justify-end p-8 border-l border-white/5 bg-gradient-to-br from-black/50 to-cyan/10 overflow-hidden">
            
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 z-0"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-cyan rounded-full blur-[100px] opacity-10"></div>

            <div class="absolute top-8 right-8 z-10 flex gap-4">
                <a href="{{ route('login') }}" class="text-white/70 hover:text-white text-sm font-bold transition">Sign In</a>
                <a href="#" class="text-crimson border border-crimson/50 hover:bg-crimson hover:text-white px-4 py-1 rounded-full text-sm font-bold transition shadow-neon">Join Us</a>
            </div>

            <div class="absolute top-24 left-8 z-10 flex items-center gap-3 bg-black/40 border border-white/10 backdrop-blur-md px-4 py-2 rounded-xl">
                <span class="text-2xl">🎮</span>
                <div class="text-xs font-bold text-white uppercase tracking-wider leading-tight">YouCode Arena<br><span class="text-cyan">Competitors</span></div>
            </div>

            <div class="absolute top-32 right-8 z-10 bg-white/5 border border-white/10 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold text-white flex items-center gap-2"><span class="text-gold">🏆</span> 500+ Challenges</div>
            <div class="absolute bottom-40 left-12 z-10 bg-white/5 border border-white/10 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold text-white flex items-center gap-2"><span class="text-cyan">⚡</span> Live Tournaments</div>

            <div class="relative z-10 w-4/5 text-center mb-8 mt-12">
                <svg viewBox="0 0 320 380" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto opacity-20 mix-blend-screen drop-shadow-2xl">
                    <rect x="120" y="220" width="80" height="120" rx="20" fill="none" stroke="white" stroke-width="4"/>
                    <circle cx="160" cy="180" r="50" fill="none" stroke="white" stroke-width="4"/>
                    <rect x="80" y="230" width="35" height="90" rx="17" fill="none" stroke="white" stroke-width="4"/>
                    <rect x="205" y="230" width="35" height="90" rx="17" fill="none" stroke="white" stroke-width="4"/>
                    <rect x="130" y="165" width="80" height="28" rx="14" fill="white"/>
                    <circle cx="148" cy="179" r="10" fill="#050505"/>
                    <circle cx="172" cy="179" r="10" fill="#050505"/>
                </svg>
                <div class="font-display font-bold text-xl text-gray-500 tracking-[0.2em] uppercase mt-4">Code. Compete. Conquer.</div>
            </div>
        </div>

    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - YouCode Arena</title>

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
<body class="text-gray-200 font-sans min-h-screen relative selection:bg-crimson selection:text-white flex items-center justify-center p-4 md:p-8">

    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>

    <div class="glass-card relative z-10 w-full max-w-5xl rounded-2xl shadow-2xl flex flex-col md:flex-row overflow-hidden border-t-2 border-t-crimson">
        
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative z-20 bg-black/20">
            
            <div class="flex justify-between items-center mb-10">
                <div class="font-display font-bold text-3xl text-white tracking-widest flex items-center gap-1">
                    <span class="text-crimson">Y</span>CA.
                </div>
                <div class="bg-black/50 border border-white/10 px-3 py-1 rounded-full text-xs font-bold text-gray-400 flex items-center gap-2 cursor-pointer hover:text-white transition">
                    <span>🇲🇦</span> EN <span class="text-[10px]">▼</span>
                </div>
            </div>

            <div class="mb-8">
                <h1 class="font-display font-bold text-4xl text-white tracking-wider uppercase mb-1">Hi Challenger!</h1>
                <p class="text-gray-400 text-sm font-medium">Welcome back to YouCode Arena.</p>
            </div>

            <div class="flex flex-col gap-3 mb-6">
                <button type="button" class="w-full bg-white/5 hover:bg-white/10 border border-white/10 text-white font-sans font-medium text-sm py-3 rounded-lg transition flex items-center justify-center gap-3">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Sign in with Google
                </button>
            </div>

            <div class="flex items-center text-center mb-6 text-gray-500 text-xs font-bold tracking-widest uppercase">
                <div class="flex-grow border-t border-white/10"></div>
                <span class="px-4">Or Login with Email</span>
                <div class="flex-grow border-t border-white/10"></div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                @csrf
                <div>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Your email address" 
                           class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-crimson focus:ring-1 focus:ring-crimson transition-colors font-sans">
                </div>
                <div>
                    <input type="password" name="password" required placeholder="Password" 
                           class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-600 focus:outline-none focus:border-crimson focus:ring-1 focus:ring-crimson transition-colors font-sans">
                </div>
                
                <div class="text-right">
                    <a href="#" class="text-cyan text-sm font-bold hover:text-white transition">Forgot password?</a>
                </div>
                
                <button type="submit" class="w-full bg-crimson hover:bg-red-700 text-white font-display font-bold text-2xl tracking-widest uppercase py-3 rounded-lg transition shadow-neon mt-2">
                    Log in
                </button>
            </form>

            <div class="text-center mt-6 text-sm text-gray-400">
                Don't have an account? <a href="{{ route('register') }}" class="text-white font-bold hover:text-crimson transition">Sign up</a>
            </div>
        </div>

        <div class="hidden md:flex w-1/2 relative flex-col items-center justify-end p-8 border-l border-white/5 bg-gradient-to-br from-black/50 to-crimson/10 overflow-hidden">
            
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 z-0"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-crimson rounded-full blur-[100px] opacity-20"></div>

            <div class="absolute top-8 right-8 z-10 flex gap-4">
                <a href="#" class="text-white/70 hover:text-white text-sm font-bold transition">Sign In</a>
                <a href="#" class="text-cyan border border-cyan/50 hover:bg-cyan hover:text-black px-4 py-1 rounded-full text-sm font-bold transition shadow-cyan-glow">Join Us</a>
            </div>

            <div class="absolute top-24 left-8 z-10 flex items-center gap-3 bg-black/40 border border-white/10 backdrop-blur-md px-4 py-2 rounded-xl">
                <span class="text-2xl">🔥</span>
                <div class="text-xs font-bold text-white uppercase tracking-wider leading-tight">Gaming Space<br><span class="text-crimson">Community</span></div>
            </div>

            <div class="relative z-10 w-4/5 text-center mb-8">
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
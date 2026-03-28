<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouCodeArena — Login Preview</title>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap');

:root {
    --bg-page: #f0f0f0;
    --bg-white: #ffffff;
    --bg-dark: #0b0b0e;
    --bg-input: #f2f3f5;
    --text-black: #111111;
    --text-gray: #6b7280;
    --accent-orange: #f97316;
    --accent-orange-light: #fb923c;
    --accent-orange-dark: #ea6c05;
    --border-light: #e5e7eb;
    --graphic-bg: #f97316;
}

* { box-sizing: border-box; }

body {
    background-color: var(--bg-page);
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

body::before {
    content: '';
    position: fixed;
    top: -60px; right: -60px;
    width: 200px; height: 200px;
    background: var(--accent-orange);
    border-radius: 50%;
    opacity: 0.25; z-index: 0;
}
body::after {
    content: '';
    position: fixed;
    bottom: -80px; left: -80px;
    width: 260px; height: 260px;
    background: var(--accent-orange);
    border-radius: 50%;
    opacity: 0.20; z-index: 0;
}

.auth-container::before {
    content: '✳';
    position: fixed;
    bottom: 3.5rem; left: 2.5rem;
    font-size: 2.8rem;
    color: var(--accent-orange);
    opacity: 0.55;
    z-index: 0;
    line-height: 1;
}

.auth-container {
    display: flex;
    width: 100%;
    max-width: 1000px;
    border-radius: 28px;
    box-shadow: 0 30px 70px -15px rgba(0, 0, 0, 0.18);
    overflow: hidden;
    position: relative;
    z-index: 1;
}

.auth-form-section {
    flex: 1;
    background-color: var(--bg-white);
    border-radius: 28px 0 0 28px;
    padding: 2.5rem 3rem 3rem 3rem;
    display: flex;
    flex-direction: column;
}

.auth-top-nav-left {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2.5rem;
}

.logo-text {
    font-size: 1.4rem;
    font-weight: 900;
    color: var(--text-black);
    letter-spacing: -0.5px;
}

.lang-selector {
    border: 1px solid var(--border-light);
    padding: 0.35rem 0.9rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-black);
    display: flex;
    align-items: center;
    gap: 0.4rem;
    cursor: pointer;
}

.auth-header h1 {
    font-size: 2.8rem;
    font-weight: 900;
    color: var(--text-black);
    margin: 0 0 0.35rem 0;
    letter-spacing: -1.5px;
    line-height: 1.1;
}

.auth-header p {
    color: var(--text-gray);
    font-size: 0.85rem;
    margin: 0 0 1.75rem 0;
    font-weight: 400;
}

.btn-google, .btn-facebook {
    width: 100%;
    background-color: var(--bg-input);
    color: var(--text-black);
    border: 1.5px solid var(--border-light);
    padding: 0.85rem 1rem;
    border-radius: 50px;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    font-size: 0.88rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.7rem;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-bottom: 0.75rem;
}

.btn-google:hover, .btn-facebook:hover {
    background-color: #e9eaec;
    border-color: #d1d5db;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.btn-facebook {
    color: #1877f2;
    border-color: #dde8fd;
    background-color: #f0f5ff;
}
.btn-facebook:hover {
    background-color: #e0eaff;
    border-color: #b8d0fc;
}

.divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 1.25rem 0 1rem;
    color: var(--text-gray);
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}
.divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid var(--border-light); }
.divider:not(:empty)::before { margin-right: 0.9rem; }
.divider:not(:empty)::after  { margin-left:  0.9rem; }

.form-group { margin-bottom: 0.9rem; }

.form-group input {
    width: 100%;
    background-color: var(--bg-input);
    border: 2px solid transparent;
    color: var(--text-black);
    padding: 0.95rem 1.4rem;
    border-radius: 50px;
    font-family: 'Poppins', sans-serif;
    font-size: 0.88rem;
    transition: all 0.25s ease;
}

.form-group input::placeholder { color: #adb5bd; font-weight: 400; }
.form-group input:focus {
    outline: none;
    border-color: var(--accent-orange);
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.10);
}

.forgot-password {
    display: block;
    text-align: right;
    color: var(--accent-orange);
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 1.2rem;
    margin-top: -0.3rem;
}
.forgot-password:hover { text-decoration: underline; }

.btn-submit {
    width: 100%;
    background-color: var(--text-black);
    color: var(--bg-white);
    border: none;
    padding: 1rem;
    border-radius: 50px;
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 0.92rem;
    cursor: pointer;
    letter-spacing: 0.03em;
    transition: all 0.2s ease;
    text-transform: uppercase;
}
.btn-submit:hover {
    background-color: #1a1a1a;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.20);
}

.auth-footer {
    text-align: center;
    margin-top: 1.25rem;
    font-size: 0.80rem;
    color: var(--text-gray);
}
.auth-footer a { color: var(--accent-orange); font-weight: 700; text-decoration: none; }
.auth-footer a:hover { text-decoration: underline; }

.alert { padding: 0.9rem 1.25rem; border-radius: 16px; margin-bottom: 1.25rem; font-size: 0.82rem; }
.alert-error { background-color: #fee2e2; color: #991b1b; }
.alert-error ul { margin: 0; padding-left: 1.5rem; }
.alert-success { background-color: #d1fae5; color: #065f46; }

/* RIGHT PANEL */
.auth-graphic {
    flex: 1.05;
    background: linear-gradient(145deg, #fb923c 0%, #f97316 40%, #ea580c 100%);
    border-radius: 0 28px 28px 0;
    padding: 2rem 2rem 0 2rem;
    position: relative;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-height: 520px;
}

.auth-graphic::before {
    content: '';
    position: absolute;
    bottom: -10%; left: 50%;
    transform: translateX(-50%);
    width: 340px; height: 340px;
    background: rgba(255,255,255,0.10);
    border-radius: 50%;
    z-index: 1;
}

.auth-top-nav-right {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 1.25rem;
    z-index: 10;
    position: relative;
}

.auth-top-nav-right a {
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    font-size: 0.82rem;
    font-weight: 600;
}

.btn-join {
    border: 1.5px solid rgba(255,255,255,0.7);
    padding: 0.45rem 1.3rem;
    border-radius: 50px;
    color: #fff !important;
    transition: all 0.25s ease;
}
.btn-join:hover { background-color: rgba(255,255,255,0.2); border-color: #fff; }

.community-badge {
    position: absolute;
    top: 5.5rem; left: 2rem;
    display: flex;
    align-items: center;
    gap: 0.65rem;
    color: #fff;
    z-index: 10;
}
.community-icon {
    width: 38px; height: 38px;
    background-color: rgba(255,255,255,0.95);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.community-text { font-size: 0.78rem; font-weight: 700; line-height: 1.3; color: rgba(255,255,255,0.95); }

/* Demo character placeholder visual */
.character-showcase {
    position: absolute;
    bottom: 0; left: 50%;
    transform: translateX(-50%);
    width: 85%; height: 78%;
    z-index: 5;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}

/* SVG character placeholder for preview */
.character-svg-wrap {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}

@media (max-width: 860px) {
    .auth-container { flex-direction: column; max-width: 480px; }
    .auth-form-section { border-radius: 28px; padding: 2rem 2rem 2.5rem; }
    .auth-graphic { display: none; }
    body::before, body::after { display: none; }
}
    </style>
</head>
<body>
    <div class="auth-container">

        <!-- LEFT: Form -->
        <div class="auth-form-section">
            <div class="auth-top-nav-left">
                <div class="logo-text">YCA.</div>
                <div class="lang-selector">
                    <span>🇲🇦</span> EN <span>⌄</span>
                </div>
            </div>
            <div class="auth-header">
                <h1>Hi Challenger!</h1>
                <p>Welcome to YouCode. Community Dashboard</p>
            </div>

            <!-- Google -->
            <button type="button" class="btn-google">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Sign in with Google
            </button>

            <!-- Facebook -->
            <button type="button" class="btn-facebook">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877f2">
                    <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.97h-1.514c-1.491 0-1.956.93-1.956 1.886v2.253h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                </svg>
                Sign in with Facebook
            </button>

            <div class="divider">Login with Others</div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Your email address" />
                </div>
                <div class="form-group" style="margin-bottom:0.5rem;">
                    <input type="password" name="password" required placeholder="Password" />
                </div>
                
                <a href="#" class="forgot-password">Forgot password?</a>
                
                <button type="submit" class="btn-submit">Log in</button>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="{{route('register')}}">Sign up</a>
            </div>
        </div>

        <!-- RIGHT: Graphic -->
        <div class="auth-graphic">
            <div class="auth-top-nav-right">
                <a href="#">Sign In</a>
                <a href="#" class="btn-join">Join Us</a>
            </div>

            <div class="community-badge">
                <div class="community-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#f97316">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                </div>
                <div class="community-text">Gaming Space<br>Community</div>
            </div>

            <!-- Character placeholder — replace background-image in CSS with your PNG -->
            <div class="character-showcase">
                <svg class="character-svg-wrap" viewBox="0 0 320 380" xmlns="http://www.w3.org/2000/svg" style="opacity:0.18;">
                    <!-- Body silhouette placeholder -->
                    <ellipse cx="160" cy="340" rx="80" ry="18" fill="rgba(0,0,0,0.3)"/>
                    <rect x="120" y="220" width="80" height="120" rx="20" fill="white"/>
                    <circle cx="160" cy="180" r="50" fill="white"/>
                    <rect x="80" y="230" width="35" height="90" rx="17" fill="white"/>
                    <rect x="205" y="230" width="35" height="90" rx="17" fill="white"/>
                    <rect x="125" y="310" width="28" height="70" rx="14" fill="white"/>
                    <rect x="167" y="310" width="28" height="70" rx="14" fill="white"/>
                    <!-- Goggles -->
                    <rect x="130" y="165" width="80" height="28" rx="14" fill="rgba(0,0,0,0.5)"/>
                    <circle cx="148" cy="179" r="10" fill="rgba(100,200,255,0.6)"/>
                    <circle cx="172" cy="179" r="10" fill="rgba(100,200,255,0.6)"/>
                </svg>
            </div>
        </div>

    </div>
</body>
</html>
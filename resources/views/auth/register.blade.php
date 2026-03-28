<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouCodeArena — Register Preview</title>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap');

:root {
    --bg-page: #f0f0f0;
    --bg-white: #ffffff;
    --bg-input: #f2f3f5;
    --text-black: #111111;
    --text-gray: #6b7280;
    --accent-orange: #f97316;
    --border-light: #e5e7eb;
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
    z-index: 0; line-height: 1;
}

.auth-container {
    display: flex;
    width: 100%;
    max-width: 1000px;
    border-radius: 28px;
    box-shadow: 0 30px 70px -15px rgba(0,0,0,0.18);
    overflow: hidden;
    position: relative;
    z-index: 1;
}

/* ── LEFT ── */
.auth-form-section {
    flex: 1;
    background-color: var(--bg-white);
    border-radius: 28px 0 0 28px;
    padding: 2rem 3rem 2.5rem 3rem;
    display: flex;
    flex-direction: column;
}

.auth-top-nav-left {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.75rem;
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
    font-size: 2.4rem;
    font-weight: 900;
    color: var(--text-black);
    margin: 0 0 0.3rem 0;
    letter-spacing: -1.5px;
    line-height: 1.1;
}

.auth-header p {
    color: var(--text-gray);
    font-size: 0.85rem;
    margin: 0 0 1.5rem 0;
    font-weight: 400;
}

.form-group { margin-bottom: 0.85rem; }

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
    box-shadow: 0 0 0 4px rgba(249,115,22,0.10);
}

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
    text-transform: uppercase;
    transition: all 0.2s ease;
    margin-top: 0.5rem;
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

.alert { padding: 0.9rem 1.25rem; border-radius: 16px; margin-bottom: 1rem; font-size: 0.82rem; }
.alert-error { background-color: #fee2e2; color: #991b1b; }
.alert-error ul { margin: 0; padding-left: 1.5rem; }

/* Terms note */
.terms-note {
    font-size: 0.75rem;
    color: var(--text-gray);
    text-align: center;
    margin-top: 0.75rem;
    line-height: 1.5;
}
.terms-note a { color: var(--accent-orange); font-weight: 600; text-decoration: none; }
.terms-note a:hover { text-decoration: underline; }

/* Step indicators */
.step-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}
.step {
    width: 28px; height: 28px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.72rem;
    font-weight: 700;
}
.step.active {
    background-color: var(--accent-orange);
    color: #fff;
}
.step.inactive {
    background-color: var(--bg-input);
    color: var(--text-gray);
}
.step-line {
    flex: 1;
    height: 2px;
    background-color: var(--border-light);
    border-radius: 2px;
}
.step-line.done { background-color: var(--accent-orange); }

/* ── RIGHT ── */
.auth-graphic {
    flex: 1.05;
    background: linear-gradient(145deg, #fb923c 0%, #f97316 40%, #ea580c 100%);
    border-radius: 0 28px 28px 0;
    padding: 2rem 2rem 0 2rem;
    position: relative;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-height: 540px;
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

/* Floating perks cards on the right panel */
.perk-card {
    position: absolute;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 14px;
    padding: 0.6rem 1rem;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    z-index: 10;
    white-space: nowrap;
}
.perk-card .perk-icon { font-size: 1rem; }
.perk-card-1 { top: 6.5rem; left: 2rem; }
.perk-card-2 { top: 10rem; right: 1.5rem; }
.perk-card-3 { bottom: 12rem; left: 2rem; }

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
.btn-join:hover { background-color: rgba(255,255,255,0.2); }

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
.community-text { font-size: 0.78rem; font-weight: 700; line-height: 1.3; }

.character-showcase {
    position: absolute;
    bottom: 0; left: 50%;
    transform: translateX(-50%);
    width: 85%; height: 72%;
    background-position: bottom center;
    background-size: contain;
    background-repeat: no-repeat;
    z-index: 5;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}

/* Right panel tagline */
.graphic-tagline {
    position: absolute;
    bottom: 2rem;
    left: 0; right: 0;
    text-align: center;
    z-index: 10;
    color: rgba(255,255,255,0.7);
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

@media (max-width: 860px) {
    .auth-container { flex-direction: column; max-width: 480px; }
    .auth-form-section { border-radius: 28px; padding: 2rem; }
    .auth-graphic { display: none; }
    body::before, body::after { display: none; }
}
    </style>
</head>
<body>
    <div class="auth-container">

        <!-- LEFT: Register Form -->
        <div class="auth-form-section">
            <div class="auth-top-nav-left">
                <div class="logo-text">YCA.</div>
                <div class="lang-selector"><span>🇲🇦</span> EN <span>⌄</span></div>
            </div>

            <!-- Step indicator -->
            <div class="step-indicator">
                <div class="step active">1</div>
                <div class="step-line done"></div>
                <div class="step active">2</div>
                <div class="step-line"></div>
                <div class="step inactive">3</div>
            </div>

            <div class="auth-header">
                <h1>Join the Arena</h1>
                <p>Create your competitor profile today.</p>
            </div>

           <form method="POST" action="{{ route('register') }}">
                @csrf 

                <div class="form-group">
                    <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Your gamertag / username" />
                </div>
                <div class="form-group">
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Your email address" />
                </div>
                <div class="form-group">
                    <input type="password" name="password" required placeholder="Create password" />
                </div>
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <input type="password" name="password_confirmation" required placeholder="Confirm password" />
                </div>
                
                <button type="submit" class="btn-submit">Sign up</button>
            </form>

            <div class="terms-note">
                By signing up you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
            </div>

            <div class="auth-footer">
                Already have an account? <a href="#">Log in</a>
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
                <div class="community-text">YouCode Arena<br>Competitors</div>
            </div>

            <!-- Floating perk cards -->
            <div class="perk-card perk-card-1"><span class="perk-icon">🏆</span> 500+ Challenges</div>
            <div class="perk-card perk-card-2"><span class="perk-icon">⚡</span> Live Tournaments</div>
            <div class="perk-card perk-card-3"><span class="perk-icon">🎯</span> Track Your Rank</div>

            <!-- Character placeholder -->
            <div class="character-showcase">
                <svg viewBox="0 0 300 360" xmlns="http://www.w3.org/2000/svg" style="opacity:0.18; width:80%; height:100%;">
                    <ellipse cx="150" cy="330" rx="75" ry="16" fill="rgba(0,0,0,0.3)"/>
                    <rect x="110" y="205" width="80" height="125" rx="20" fill="white"/>
                    <circle cx="150" cy="168" r="48" fill="white"/>
                    <rect x="70" y="215" width="34" height="88" rx="17" fill="white"/>
                    <rect x="196" y="215" width="34" height="88" rx="17" fill="white"/>
                    <rect x="116" y="295" width="27" height="65" rx="13" fill="white"/>
                    <rect x="157" y="295" width="27" height="65" rx="13" fill="white"/>
                    <rect x="120" y="152" width="78" height="26" rx="13" fill="rgba(0,0,0,0.45)"/>
                    <circle cx="138" cy="165" r="9" fill="rgba(100,200,255,0.55)"/>
                    <circle cx="162" cy="165" r="9" fill="rgba(100,200,255,0.55)"/>
                </svg>
            </div>

            <div class="graphic-tagline">Code. Compete. Conquer.</div>
        </div>

    </div>
</body>
</html>
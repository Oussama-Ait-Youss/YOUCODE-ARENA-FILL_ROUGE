<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouCodeArena — Login</title>
    @vite(['resources/css/gaming-auth.css'])
</head>
<body>
    
    <div class="glow-orb-1"></div>
    <div class="glow-orb-2"></div>

    <div class="auth-container">
        
        <div class="auth-header">
            <h1>WELCOME BACK</h1>
            <p>Enter the arena to continue your journey</p>
        </div>

        {{-- Session status and errors logic remains unchanged --}}
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email" />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password" />
            </div>

            <div class="remember-group">
                <input type="checkbox" id="remember" name="remember" />
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn-submit">Initialize Login sequence</button>
        </form>

        <div class="auth-footer">
            <p>Don't have an arena pass? <a href="{{ route('register') }}">Create Account</a></p>
        </div>

    </div>

</body>
</html>
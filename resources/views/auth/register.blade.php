<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouCodeArena — Register</title>
    <!-- Vite for referencing the custom CSS -->
    @vite(['resources/css/gaming-auth.css'])
</head>
<body>
    
    <!-- Decorative Glowing Orbs -->
    <div class="glow-orb-1"></div>
    <div class="glow-orb-2"></div>

    <div class="auth-container">
        
        <div class="auth-header">
            <h1>JOIN THE ARENA</h1>
            <p>Create your profile and start playing</p>
        </div>

        {{-- Global errors --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Username --}}
            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                    placeholder="Choose an epic handle"
                />
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="Enter your email"
                />
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Enter your password"
                />
            </div>

            {{-- Confirm Password --}}
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    placeholder="Re-enter your password"
                />
            </div>

            <button type="submit" class="btn-submit">Register Profile</button>

        </form>

        <div class="auth-footer">
            <p>Already an arena member? <a href="{{ route('login') }}">Access Terminal</a></p>
        </div>

    </div>

</body>
</html>
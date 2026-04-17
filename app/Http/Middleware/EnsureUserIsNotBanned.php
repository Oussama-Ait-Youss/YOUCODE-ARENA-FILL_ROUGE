<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isBanned()) {
            return $next($request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Votre compte a été suspendu par un administrateur.',
            ], 403);
        }

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Votre compte a été suspendu par un administrateur.',
            ]);
    }
}

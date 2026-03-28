<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Organizer\TournamentController;
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});

// RBAC
Route::middleware('auth')->group(function () {
    
    // Route globale pour tous les utilisateurs connectés
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Route exclusive à l'Admin (God Mode)
    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return 'Accès autorisé : Bonjour Administrateur.';
        });
    });

    // Route exclusive à l'Organisateur
    Route::middleware(['auth', 'role:Organisateur'])->prefix('organizer')->name('organizer.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('organizer.dashboard'); 
    })->name('dashboard');

    
    Route::resource('tournaments', TournamentController::class);
    
});

});
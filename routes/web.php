<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Organizer\TournamentController;
use App\Http\Controllers\Competitor\TournamentExplorerController;
use App\Http\Controllers\Competitor\TeamController;


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
// --- ROUTES POUR LES COMPÉTITEURS --- //
Route::middleware(['auth', 'role:Compétiteur'])->prefix('competitor')->name('competitor.')->group(function () {
    
    // Le catalogue des tournois ouverts
    Route::get('/tournaments', [TournamentExplorerController::class, 'index'])->name('tournaments.index');
    
});
Route::middleware(['auth', 'role:Compétiteur'])->prefix('competitor')->name('competitor.')->group(function () {
    
    // Le catalogue
    Route::get('/tournaments', [TournamentExplorerController::class, 'index'])->name('tournaments.index');
    
   
    Route::get('/tournaments/{tournament}/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/tournaments/{tournament}/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/my-teams', [TeamController::class, 'index'])->name('teams.index');
    
});
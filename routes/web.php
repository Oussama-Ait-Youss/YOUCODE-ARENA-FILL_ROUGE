<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Organizer\TournamentController;
use App\Http\Controllers\Competitor\TournamentExplorerController;
use App\Http\Controllers\Competitor\TeamController;
use App\Http\Controllers\Competitor\DashboardController;
use App\Http\Controllers\Competitor\CommentController;
use App\Http\Controllers\Admin\TournamentsController;


Route::get('/', function () {
    return view('home');
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

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return 'Accès autorisé : Bonjour Administrateur.';
        });
    });

    Route::middleware(['auth', 'role:Organisateur'])->prefix('organizer')->name('organizer.')->group(function () {
    
        Route::get('/dashboard', function () {
            return view('organizer.dashboard'); 
        })->name('dashboard'); 
        
        Route::resource('tournaments', TournamentController::class);
        
        
        Route::get('/tournaments/{tournament}/matches', [App\Http\Controllers\Organizer\MatchController::class, 'index'])->name('matches.index');
        Route::post('/tournaments/{tournament}/matches', [App\Http\Controllers\Organizer\MatchController::class, 'store'])->name('matches.store');
            Route::patch('/tournaments/{tournament}/matches/{match}', [App\Http\Controllers\Organizer\MatchController::class, 'updateScore'])->name('matches.update_score');
    });

});


Route::middleware(['auth', 'role:Compétiteur'])->prefix('competitor')->name('competitor.')->group(function () {
    
    Route::get('/tournaments', [TournamentExplorerController::class, 'index'])->name('tournaments.index');
        Route::get('/feed', [\App\Http\Controllers\Competitor\FeedController::class, 'index'])->name('feed.index');
        Route::post('/feed', [\App\Http\Controllers\Competitor\FeedController::class, 'store'])->name('feed.store');
        Route::get('/profile', function () { 
            $user = auth()->user();
            
            $myTournaments = \App\Models\Tournament::whereHas('teams.members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

            return view('competitor.profile', compact('user', 'myTournaments')); 
        })->name('profile');
        Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
       
    
});

Route::middleware(['auth', 'role:Compétiteur'])->prefix('competitor')->name('competitor.')->group(function () {
    
    Route::get('/tournaments', [TournamentExplorerController::class, 'index'])->name('tournaments.index');
    
   
    Route::get('/tournaments/{tournament}/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/tournaments/{tournament}/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/my-teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/tournaments/{tournament}', [TournamentExplorerController::class, 'show'])->name('tournaments.show');
    Route::get('/leaderboard', [\App\Http\Controllers\Competitor\LeaderboardController::class, 'index'])->name('leaderboard');
    
});
Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::put('/users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'changeRole'])->name('users.change_role');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/tournaments', [\App\Http\Controllers\Admin\TournamentsController::class, 'index'])->name('tournaments.index');
        Route::delete('/tournaments/{tournament}', [\App\Http\Controllers\Admin\TournamentsController::class, 'destroy'])->name('tournaments.destroy');
    });
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Organizer\TournamentController;
use App\Http\Controllers\Competitor\TournamentExplorerController;
use App\Http\Controllers\Competitor\TeamController;
use App\Http\Controllers\Competitor\DashboardController;
use App\Http\Controllers\Competitor\CommentController;
use App\Http\Controllers\Competitor\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
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

Route::middleware(['auth', 'not_banned'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['auth', 'role:Organisateur'])->prefix('organizer')->name('organizer.')->group(function () {
    
        Route::get('/dashboard', [TournamentController::class, 'index'])->name('dashboard');
        
        
        Route::get('/tournaments/{tournament}/data', [\App\Http\Controllers\Organizer\TournamentController::class, 'data'])->name('tournaments.data');
        Route::resource('tournaments', TournamentController::class);
        Route::patch('/tournaments/{tournament}/status', [TournamentController::class, 'updateStatus'])->name('tournaments.update_status');
        
        Route::get('/tournaments/{tournament}/matches', [App\Http\Controllers\Organizer\MatchController::class, 'index'])->name('matches.index');
        Route::post('/tournaments/{tournament}/matches', [App\Http\Controllers\Organizer\MatchController::class, 'store'])->name('matches.store');
        Route::patch('/tournaments/{tournament}/matches/{match}', [App\Http\Controllers\Organizer\MatchController::class, 'updateScore'])->name('matches.update_score');
        
        // Dynamic Bracket
        Route::post('/tournaments/{tournament}/bracket/generate', [App\Http\Controllers\Organizer\MatchController::class, 'generateBracket'])->name('matches.generate_bracket');
        Route::post('/tournaments/{tournament}/bracket', [App\Http\Controllers\Organizer\MatchController::class, 'updateBracket'])->name('matches.update_bracket');
        Route::post('/tournaments/{tournament}/bracket/winner', [App\Http\Controllers\Organizer\MatchController::class, 'setWinner'])->name('matches.set_winner');

        // Participants mapping
        Route::patch('/tournaments/{tournament}/participants/{registration}/accept', [App\Http\Controllers\Organizer\RegistrationController::class, 'accept'])->name('tournaments.participants.accept');
        Route::patch('/tournaments/{tournament}/participants/{registration}/reject', [App\Http\Controllers\Organizer\RegistrationController::class, 'reject'])->name('tournaments.participants.reject');
    });

});


Route::middleware(['auth', 'not_banned', 'role:Compétiteur'])->prefix('competitor')->name('competitor.')->group(function () {
    Route::get('/tournaments', [TournamentExplorerController::class, 'index'])->name('tournaments.index');
    Route::get('/feed', [\App\Http\Controllers\Competitor\FeedController::class, 'index'])->name('feed.index');
    Route::post('/feed', [\App\Http\Controllers\Competitor\FeedController::class, 'store'])->name('feed.store');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/tournaments/{tournament}/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/tournaments/{tournament}/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::delete('/tournaments/{tournament}/registration', [TeamController::class, 'leave'])->name('tournaments.leave');
    Route::post('/teams/accept/{tournament}', [TeamController::class, 'acceptInvite'])->name('teams.accept');
Route::post('/teams/decline/{tournament}', [TeamController::class, 'declineInvite'])->name('teams.decline');
    Route::get('/my-teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/tournaments/{tournament}', [TournamentExplorerController::class, 'show'])->name('tournaments.show');
    Route::get('/leaderboard', [\App\Http\Controllers\Competitor\LeaderboardController::class, 'index'])->name('leaderboard');
    
});

Route::middleware(['auth', 'not_banned', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}/role', [AdminUserController::class, 'changeRole'])->name('users.change_role');
    Route::patch('/users/{user}/status', [AdminUserController::class, 'toggleBan'])->name('users.toggle_ban');
    Route::get('/tournaments', [TournamentsController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [TournamentsController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [TournamentsController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}/edit', [TournamentsController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [TournamentsController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [TournamentsController::class, 'destroy'])->name('tournaments.destroy');
});

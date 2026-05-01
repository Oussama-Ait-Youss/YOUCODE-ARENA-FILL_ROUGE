<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Game;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $games = Game::orderBy('name')->get();

        $activeGame = $request->query('game');

        $posts = Post::with(['author', 'comments.author', 'game'])
            ->latest();

        if ($activeGame) {
            $posts->where('game_id', (int) $activeGame);
        }

        $posts = $posts->get();

        return view('competitor.dashboard', compact(
            'posts',
            'games',
            'activeGame'
        ));
    }
}

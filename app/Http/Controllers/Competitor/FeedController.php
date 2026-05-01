<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $activeGame = $request->query('game');

        $query = Post::with(['author', 'comments.author', 'game'])->latest();

        if ($activeGame) {
            $query->where('game_id', (int) $activeGame);
        }

        $posts = $query->get();
        $games = Game::orderBy('name')->get();

        return view('competitor.feed.index', compact('posts', 'games', 'activeGame'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Organisateur'), 403);

        $validated = $request->validate([
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'game_id' => 'nullable|exists:games,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'author_id' => auth()->id(),
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'game_id' => $validated['game_id'] ?? null,
        ]);

        return redirect()
            ->route('dashboard', array_filter(['game' => $validated['game_id'] ?? null]))
            ->with('success', 'Message publié avec succès !');
    }
}

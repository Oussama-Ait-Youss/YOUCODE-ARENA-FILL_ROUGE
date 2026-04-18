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
        $categories = Game::all();

        $active_category = $request->query('category');

        $posts = Post::with(['author', 'comments', 'category'])
            ->latest();

        if ($active_category) {
            $posts->where('category_id', (int) $active_category);
        }

        $posts = $posts->get();

        return view('competitor.dashboard', compact(
            'posts',
            'categories',
            'active_category'
        ));
    }
}
<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Game; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $categories = collect([
            ['name' => 'All', 'slug' => 'all', 'icon' => '🔥']
        ]);

        
        $dbGames = Game::all(); 

        foreach ($dbGames as $game) {
            $categories->push([
                'name' => $game->name,
                
                'slug' => Str::slug($game->name), 
                
                'icon' => '' 
            ]);
        }

        
        $active_category = $request->query('category', 'all');

        $posts = Post::with(['author', 'comments'])->latest()->get();

        return view('competitor.dashboard', compact('posts', 'categories', 'active_category'));
    }
}
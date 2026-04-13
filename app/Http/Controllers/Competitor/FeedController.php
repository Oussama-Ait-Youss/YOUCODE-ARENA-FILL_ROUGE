<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        
        $posts = Post::with(['author', 'comments.author'])
                     ->latest() 
                     ->get();

        return view('competitor.feed.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('posts', 'public');
    }

        Post::create([
            'author_id' => auth()->id(),
            'content' => $request->content,
            'image_path' => $imagePath,
            
        ]);

        return redirect()->back()->with('success', 'Message publié avec succès !');
    }
}
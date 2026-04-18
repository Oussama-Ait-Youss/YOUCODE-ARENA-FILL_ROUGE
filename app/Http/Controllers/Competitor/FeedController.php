<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
{
    $active_category = $request->query('category');

    $query = Post::with(['author', 'comments.author'])->latest();

    if ($active_category) {
    $query->where('category_id', (int) $active_category);
    }

    $posts = $query->get();
    
    $categories = \Illuminate\Support\Facades\DB::table('categories')->get();

    return view('competitor.feed.index', compact('posts', 'categories', 'active_category'));
}

public function store(Request $request)
{

    $request->validate([
        'content' => 'required|string|max:500',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'category_id' => 'nullable|exists:categories,id', 
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('posts', 'public');
    }

    Post::create([
        'author_id' => auth()->id(),
        'content' => $request->content,
        'image_path' => $imagePath,
        'category_id' => $request->category_id, 
    ]);

    return redirect()->back()->with('success', 'Message publié avec succès !');
}
}

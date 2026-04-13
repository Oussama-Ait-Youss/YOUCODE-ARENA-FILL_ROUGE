<?php

namespace App\Http\Controllers\Competitor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        
        Comment::create([
            'post_id' => $post->id,
            'author_id' => auth()->id(), 
            'content' => $request->content,
        ]);

        return back();
    }
}
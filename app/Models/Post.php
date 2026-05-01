<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'author_id', 
        'game_id',
        'match_id',
        'image_path',
        'content',
    ];

  
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    
}

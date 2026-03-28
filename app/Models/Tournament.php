<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Tournament extends Model
{
    protected $fillable = [
        'organizer_id',
        'game_id',
        'category_id',
        'title',
        'status',
        'max_capacity',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];


    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

   
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

   
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class); 
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }


}

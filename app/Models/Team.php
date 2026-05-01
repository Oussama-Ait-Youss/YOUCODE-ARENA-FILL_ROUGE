<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id', 
        'name',
    ];

    
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}

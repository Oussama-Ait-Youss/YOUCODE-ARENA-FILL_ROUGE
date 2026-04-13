<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matchh extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'tournament_id', 
        'challenge_id', 
        'team1_id', 
        'team2_id', 
        'winner_team_id', 
        'score',          
        'status', 
        'played_at'
    ];

    public function tournament(): BelongsTo {
        return $this->belongsTo(Tournament::class);
    }

    public function team1(): BelongsTo {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2(): BelongsTo {
        return $this->belongsTo(Team::class, 'team2_id');
    }
}
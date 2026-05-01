<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matchh extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'tournament_id', 
        'team1_id', 
        'team2_id', 
        'winner_team_id', 
        'score',          
        'status', 
        'played_at',
        'next_match_id',
        'round',
        'position_in_round'
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

    public function winnerTeam(): BelongsTo {
        return $this->belongsTo(Team::class, 'winner_team_id');
    }

    public function nextMatch(): BelongsTo {
        return $this->belongsTo(self::class, 'next_match_id');
    }

    public function loserTeam(): ?Team
    {
        if (!$this->winner_team_id) {
            return null;
        }

        if ($this->winner_team_id === $this->team1_id) {
            return $this->team2;
        }

        if ($this->winner_team_id === $this->team2_id) {
            return $this->team1;
        }

        return null;
    }
}

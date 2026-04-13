<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitorProfile extends Model
{
    
    protected $primaryKey = 'user_id';
    
    
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'games_won',
        'games_loss'
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
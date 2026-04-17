<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Registration extends Model
{
    protected $fillable = [
        'user_id',
        'tournament_id',
        'status',
        'registration_date'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }


    public function tournament(){
        return $this->belongsTo(Tournament::class);
    }
}

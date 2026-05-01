<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function tournaments(){
        return $this->hasMany(Tournament::class);

    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function requiresTeamInvite(): bool
    {
        $name = mb_strtolower($this->name ?? '');

        return str_contains($name, 'babyfoot');
    }

}

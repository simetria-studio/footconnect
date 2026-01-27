<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'scout_id',
        'player_id',
    ];

    public function scout()
    {
        return $this->belongsTo(User::class, 'scout_id');
    }

    public function player()
    {
        return $this->belongsTo(User::class, 'player_id');
    }
}


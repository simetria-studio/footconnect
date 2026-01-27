<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_profile_id',
        'season',
        'matches_played',
        'goals',
        'assists',
        'minutes_played',
    ];

    public function playerProfile()
    {
        return $this->belongsTo(PlayerProfile::class);
    }
}


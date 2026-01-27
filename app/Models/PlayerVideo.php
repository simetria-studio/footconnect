<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_profile_id',
        'title',
        'provider',
        'url',
        'display_order',
    ];

    public function playerProfile()
    {
        return $this->belongsTo(PlayerProfile::class);
    }
}


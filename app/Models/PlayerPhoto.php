<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_profile_id',
        'path',
        'caption',
        'display_order',
    ];

    public function playerProfile()
    {
        return $this->belongsTo(PlayerProfile::class);
    }
}


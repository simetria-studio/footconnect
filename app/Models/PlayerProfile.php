<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position',
        'age',
        'height_cm',
        'weight_kg',
        'current_club',
        'city',
        'state',
        'dominant_foot',
        'profile_photo_path',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->hasMany(PlayerVideo::class);
    }

    public function photos()
    {
        return $this->hasMany(PlayerPhoto::class);
    }

    public function stats()
    {
        return $this->hasMany(PlayerStat::class);
    }
}


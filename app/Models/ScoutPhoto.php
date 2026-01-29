<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'scout_profile_id',
        'path',
        'caption',
        'display_order',
    ];

    public function scoutProfile()
    {
        return $this->belongsTo(ScoutProfile::class);
    }
}

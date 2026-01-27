<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'professional_type',
        'organization',
        'city',
        'state',
        'website',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


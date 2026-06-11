<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoutProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'professional_type',
        'organization',
        'has_company',
        'company_name',
        'scope',
        'city',
        'state',
        'country',
        'is_federated',
        'federation_name',
        'website',
        'bio',
    ];

    protected function casts(): array
    {
        return [
            'has_company' => 'boolean',
            'is_federated' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(ScoutPhoto::class);
    }

    public function getScopeLabelAttribute(): ?string
    {
        return match ($this->scope) {
            'regional' => 'Regional',
            'nacional' => 'Nacional',
            'internacional' => 'Internacional',
            default => null,
        };
    }
}

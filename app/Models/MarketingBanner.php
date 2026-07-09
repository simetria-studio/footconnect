<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MarketingBanner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'link_url',
        'cta_label',
        'audience',
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }

    public function scopeForAudience(Builder $query, ?string $role): Builder
    {
        // Landing pública: só banners marcados para todos
        if ($role === null) {
            return $query->where('audience', 'all');
        }

        return $query->where(function (Builder $q) use ($role) {
            $q->where('audience', 'all');

            if (in_array($role, ['player', 'scout'], true)) {
                $q->orWhere('audience', $role);
            }
        });
    }

    public function getAudienceLabelAttribute(): string
    {
        return match ($this->audience) {
            'player' => 'Jogadores',
            'scout' => 'Profissionais',
            default => 'Todos',
        };
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }
}

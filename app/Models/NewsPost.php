<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'image_path',
        'audience',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (NewsPost $post) {
            if (blank($post->slug)) {
                $post->slug = static::uniqueSlug($post->title, $post->id);
            }

            if ($post->is_published && blank($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'noticia';
        $slug = $base;
        $i = 2;

        while (static::query()
            ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeForAudience(Builder $query, ?string $role): Builder
    {
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

    public function getExcerptOrBodyAttribute(): string
    {
        return $this->excerpt ?: Str::limit(strip_tags($this->body), 140);
    }
}

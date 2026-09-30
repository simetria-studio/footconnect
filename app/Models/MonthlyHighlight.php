<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyHighlight extends Model
{
    public const CATEGORY_PLAYER = 'player';

    public const CATEGORY_COACH = 'coach';

    public const CATEGORY_BUSINESSMAN = 'businessman';

    public const CATEGORIES = [
        self::CATEGORY_PLAYER,
        self::CATEGORY_COACH,
        self::CATEGORY_BUSINESSMAN,
    ];

    public const PLAN_GROUP_MAP = [
        'g1' => self::CATEGORY_PLAYER,
        'g2' => self::CATEGORY_BUSINESSMAN,
        'g3' => self::CATEGORY_COACH,
    ];

    protected $fillable = [
        'category',
        'name',
        'role_title',
        'organization',
        'story',
        'photo_path',
        'video_path',
        'video_url',
        'month',
        'year',
        'sort_order',
        'is_active',
        'submitted_at',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'integer',
            'year' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForPeriod(Builder $query, ?int $year = null, ?int $month = null): Builder
    {
        $year ??= (int) now()->year;
        $month ??= (int) now()->month;

        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeCurrentMonth(Builder $query): Builder
    {
        return $query->active()->forPeriod();
    }

    public function displayName(): string
    {
        return $this->user?->full_name
            ?: $this->user?->name
            ?: $this->name
            ?: 'Usuário';
    }

    public function displayRoleTitle(): ?string
    {
        $user = $this->user;

        if (! $user) {
            return $this->role_title;
        }

        if ($this->category === self::CATEGORY_PLAYER) {
            return $user->playerProfile?->position ?: $this->role_title;
        }

        return config('plans.groups.'.$user->plan_group.'.short_label')
            ?: $this->role_title;
    }

    public function displayOrganization(): ?string
    {
        $user = $this->user;

        if (! $user) {
            return $this->organization;
        }

        if ($this->category === self::CATEGORY_PLAYER) {
            $profile = $user->playerProfile;

            return $profile?->institution_name
                ?: $profile?->current_club
                ?: $this->organization;
        }

        $profile = $user->scoutProfile;

        return $profile?->company_name
            ?: $profile?->organization
            ?: $this->organization;
    }

    public function displayStory(): ?string
    {
        $user = $this->user;

        if ($this->category === self::CATEGORY_PLAYER) {
            return $user?->playerProfile?->story ?: $this->story;
        }

        return $user?->scoutProfile?->story ?: $this->story;
    }

    public function displayPhotoUrl(): ?string
    {
        $user = $this->user;

        if (! $user) {
            return $this->photo_path ? asset('storage/'.$this->photo_path) : null;
        }

        if ($this->category === self::CATEGORY_PLAYER) {
            $profile = $user->playerProfile;

            if ($profile?->profile_photo_path) {
                return asset('storage/'.$profile->profile_photo_path);
            }

            $photo = $profile?->photos?->sortBy('display_order')->first()
                ?: $profile?->photos?->first();

            return $photo?->path ? asset('storage/'.$photo->path) : null;
        }

        $photo = $user->scoutProfile?->photos?->sortBy('display_order')->first()
            ?: $user->scoutProfile?->photos?->first();

        return $photo?->path ? asset('storage/'.$photo->path) : null;
    }

    public function displayVideoUrl(): ?string
    {
        $user = $this->user;

        if ($this->category === self::CATEGORY_PLAYER) {
            $video = $user?->playerProfile?->videos?->sortBy('display_order')->first()
                ?: $user?->playerProfile?->videos?->first();

            return $video?->url;
        }

        return $user?->scoutProfile?->video_url ?: $this->video_url;
    }

    public function displayEmbedVideoUrl(): ?string
    {
        $url = $this->displayVideoUrl();

        if (! $url) {
            return null;
        }

        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{6,})~', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $matches)) {
            return 'https://player.vimeo.com/video/'.$matches[1];
        }

        return null;
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            self::CATEGORY_PLAYER => __('ui.highlights.categories.player'),
            self::CATEGORY_COACH => __('ui.highlights.categories.coach'),
            self::CATEGORY_BUSINESSMAN => __('ui.highlights.categories.businessman'),
            default => (string) $this->category,
        };
    }

    public function getPeriodLabelAttribute(): string
    {
        $months = [
            1 => __('ui.highlights.months.1'),
            2 => __('ui.highlights.months.2'),
            3 => __('ui.highlights.months.3'),
            4 => __('ui.highlights.months.4'),
            5 => __('ui.highlights.months.5'),
            6 => __('ui.highlights.months.6'),
            7 => __('ui.highlights.months.7'),
            8 => __('ui.highlights.months.8'),
            9 => __('ui.highlights.months.9'),
            10 => __('ui.highlights.months.10'),
            11 => __('ui.highlights.months.11'),
            12 => __('ui.highlights.months.12'),
        ];

        return ($months[$this->month] ?? $this->month).' '.$this->year;
    }

    public static function categoryOptions(): array
    {
        return [
            self::CATEGORY_PLAYER => __('ui.highlights.categories.player'),
            self::CATEGORY_COACH => __('ui.highlights.categories.coach'),
            self::CATEGORY_BUSINESSMAN => __('ui.highlights.categories.businessman'),
        ];
    }

    public static function categoryFromPlanGroup(?string $planGroup): ?string
    {
        return self::PLAN_GROUP_MAP[$planGroup] ?? null;
    }

    public static function planGroupsForCategory(string $category): array
    {
        return array_keys(array_filter(
            self::PLAN_GROUP_MAP,
            fn (string $mapped) => $mapped === $category
        ));
    }
}

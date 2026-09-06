<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    protected $fillable = [
        'plan_key',
        'amount_cents',
        'currency',
        'interval',
        'interval_count',
        'name',
        'description',
        'display_label',
        'sort_order',
        'is_active',
        'trial_days',
    ];

    protected function casts(): array
    {
        return [
            'amount_cents' => 'integer',
            'is_active' => 'boolean',
            'trial_days' => 'integer',
        ];
    }

    public function getAmountReaisAttribute(): float
    {
        return round($this->amount_cents / 100, 2);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'R$ '.number_format($this->amount_reais, 2, ',', '.');
    }

    public static function getByKey(string $planKey): ?self
    {
        return static::where('plan_key', $planKey)->where('is_active', true)->first();
    }

    public static function getPlansForOnboarding(): \Illuminate\Support\Collection
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }

    public function hasTrial(): bool
    {
        return (int) $this->trial_days > 0;
    }

    public function trialLabel(): ?string
    {
        if (! $this->hasTrial()) {
            return null;
        }

        return ((int) $this->trial_days) === 30
            ? '1 mês grátis'
            : $this->trial_days.' dias grátis';
    }

    public function groupKey(): string
    {
        return explode('_', (string) $this->plan_key)[0] ?? '';
    }

    public static function groupHasTrial(string $groupKey): bool
    {
        return static::query()
            ->where('plan_key', 'like', $groupKey.'_%')
            ->where('is_active', true)
            ->where('trial_days', '>', 0)
            ->exists();
    }
}

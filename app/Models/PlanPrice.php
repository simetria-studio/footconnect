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
    ];

    protected function casts(): array
    {
        return [
            'amount_cents' => 'integer',
            'is_active' => 'boolean',
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
}

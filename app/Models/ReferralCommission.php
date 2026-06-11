<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralCommission extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'stripe_invoice_id',
        'payment_cents',
        'commission_percent',
        'commission_cents',
        'status',
        'is_counted',
        'compensates_at',
        'available_at',
        'withdrawal_id',
    ];

    protected function casts(): array
    {
        return [
            'compensates_at' => 'datetime',
            'available_at' => 'datetime',
            'is_counted' => 'boolean',
        ];
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    public function withdrawal(): BelongsTo
    {
        return $this->belongsTo(ReferralWithdrawal::class, 'withdrawal_id');
    }

    public function getFormattedCommissionAttribute(): string
    {
        return 'R$ '.number_format($this->commission_cents / 100, 2, ',', '.');
    }
}

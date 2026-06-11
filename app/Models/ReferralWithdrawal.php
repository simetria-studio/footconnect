<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralWithdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount_cents',
        'pix_key',
        'pix_key_type',
        'status',
        'is_automatic',
        'processed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_automatic' => 'boolean',
            'processed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(ReferralCommission::class, 'withdrawal_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'R$ '.number_format($this->amount_cents / 100, 2, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'completed' => 'Pago via PIX',
            'failed' => 'Falhou',
            default => $this->status,
        };
    }
}

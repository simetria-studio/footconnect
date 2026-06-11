<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'full_name',
        'email',
        'password',
        'role',
        'plan_group',
        'referral_code',
        'referred_by_id',
        'pix_key',
        'pix_key_type',
        'referral_program_blocked',
        'referral_registration_ip',
        'referral_is_counted',
        'referral_invalid_reason',
        'is_admin',
        'is_active',
        'city',
        'state',
        'country',
        'plan_type',
        'plan_interval',
        'stripe_customer_id',
        'stripe_subscription_id',
        'subscription_status',
        'current_period_end',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'current_period_end' => 'datetime',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'referral_program_blocked' => 'boolean',
            'referral_is_counted' => 'boolean',
        ];
    }

    public function scopeValidReferralsOf($query, int $referrerId)
    {
        return $query->where('referred_by_id', $referrerId)
            ->where('referral_is_counted', true)
            ->where('is_active', true);
    }

    public function playerProfile()
    {
        return $this->hasOne(PlayerProfile::class);
    }

    public function scoutProfile()
    {
        return $this->hasOne(ScoutProfile::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'user_one_id')
            ->orWhere('user_two_id', $this->id);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function favoritePlayers()
    {
        return $this->hasMany(Favorite::class, 'scout_id');
    }

    public function favoritedByScouts()
    {
        return $this->hasMany(Favorite::class, 'player_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    public function referralCommissionsEarned()
    {
        return $this->hasMany(ReferralCommission::class, 'referrer_id');
    }

    public function referralWithdrawals()
    {
        return $this->hasMany(ReferralWithdrawal::class);
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isActive(): bool
    {
        return (bool) ($this->is_active ?? true);
    }
}

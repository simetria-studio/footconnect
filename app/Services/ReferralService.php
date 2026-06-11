<?php

namespace App\Services;

use App\Models\ReferralCommission;
use App\Models\ReferralWithdrawal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReferralService
{
    public function ensureReferralCode(User $user): User
    {
        if ($user->referral_code) {
            return $user;
        }

        do {
            $code = config('referrals.code_prefix').strtoupper(Str::random(4));
        } while (User::where('referral_code', $code)->exists());

        $user->referral_code = $code;
        $user->save();

        return $user;
    }

    public function getReferralLink(User $user): string
    {
        $user = $this->ensureReferralCode($user);

        return route('referral.capture', $user->referral_code);
    }

    public function attributeReferral(User $newUser, ?string $code, ?string $ip = null): void
    {
        if ($ip) {
            $newUser->referral_registration_ip = $ip;
        }

        if (! $code) {
            $newUser->save();

            return;
        }

        $referrer = User::where('referral_code', strtoupper($code))->first();

        if (! $referrer) {
            $newUser->referral_registration_ip = $ip;
            $newUser->save();

            return;
        }

        if ($this->isSelfReferral($referrer, $newUser, $ip)) {
            $newUser->referral_registration_ip = $ip;
            $newUser->referral_is_counted = false;
            $newUser->referral_invalid_reason = 'Autoindicação proibida';
            $newUser->save();

            return;
        }

        if ($referrer->referral_program_blocked) {
            $newUser->referral_registration_ip = $ip;
            $newUser->save();

            return;
        }

        $newUser->referred_by_id = $referrer->id;
        $newUser->referral_registration_ip = $ip;

        if ($this->isSameIpAsReferrer($referrer, $ip)) {
            $newUser->referral_is_counted = false;
            $newUser->referral_invalid_reason = 'Mesmo IP do indicador';
        }

        $newUser->save();

        $this->checkSpamAndBlock($referrer, $ip);
    }

    public function updateCustomCode(User $user, string $code): User
    {
        if ($user->referral_program_blocked) {
            throw ValidationException::withMessages([
                'referral_code' => 'Seu programa de indicação está bloqueado.',
            ]);
        }

        $code = strtoupper(trim($code));
        $pattern = config('referrals.custom_code_pattern');

        if (! preg_match($pattern, $code)) {
            throw ValidationException::withMessages([
                'referral_code' => 'Use um código entre '.config('referrals.custom_code_min_length').' e '.config('referrals.custom_code_max_length').' caracteres, começando com FOOT (ex: FOOT23).',
            ]);
        }

        if (User::where('referral_code', $code)->where('id', '!=', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'referral_code' => 'Este código já está em uso. Escolha outro.',
            ]);
        }

        $user->referral_code = $code;
        $user->save();

        return $user;
    }

    public function recordCommissionFromInvoice(User $referredUser, string $invoiceId, int $amountPaidCents, \DateTimeInterface $paidAt): ?ReferralCommission
    {
        if (! $referredUser->referred_by_id || $amountPaidCents <= 0) {
            return null;
        }

        if (! $this->isReferralEligibleForCommission($referredUser)) {
            return null;
        }

        $referrer = User::find($referredUser->referred_by_id);

        if (! $referrer || $referrer->referral_program_blocked) {
            return null;
        }

        if (ReferralCommission::where('stripe_invoice_id', $invoiceId)->exists()) {
            return null;
        }

        $percent = config('referrals.commission_percent', 25);
        $commissionCents = (int) round($amountPaidCents * ($percent / 100));
        $availableAt = Carbon::parse($paidAt)->addDays(config('referrals.payout_delay_days', 2));

        return ReferralCommission::create([
            'referrer_id' => $referredUser->referred_by_id,
            'referred_id' => $referredUser->id,
            'stripe_invoice_id' => $invoiceId,
            'payment_cents' => $amountPaidCents,
            'commission_percent' => $percent,
            'commission_cents' => $commissionCents,
            'status' => 'pending',
            'is_counted' => true,
            'compensates_at' => $paidAt,
            'available_at' => $availableAt,
        ]);
    }

    public function getDashboardStats(User $user): array
    {
        $user = $this->ensureReferralCode($user);

        $validReferralsQuery = User::validReferralsOf($user->id);

        $referralsCount = (clone $validReferralsQuery)->count();
        $activeReferrals = (clone $validReferralsQuery)
            ->where('subscription_status', 'active')
            ->count();

        $commissionQuery = ReferralCommission::where('referrer_id', $user->id)->where('is_counted', true);

        $totalEarningsCents = (clone $commissionQuery)->sum('commission_cents');
        $pendingCents = (clone $commissionQuery)->where('status', 'pending')->sum('commission_cents');
        $availableCents = (clone $commissionQuery)->where('status', 'available')->sum('commission_cents');

        $withdrawnCents = ReferralWithdrawal::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount_cents');

        return [
            'referrals_count' => $referralsCount,
            'active_referrals' => $activeReferrals,
            'total_earnings_cents' => $totalEarningsCents,
            'pending_cents' => $pendingCents,
            'available_cents' => $availableCents,
            'withdrawn_cents' => $withdrawnCents,
            'commission_percent' => config('referrals.commission_percent', 25),
            'is_blocked' => (bool) $user->referral_program_blocked,
        ];
    }

    public function getOfficialRanking(int $limit = 20): \Illuminate\Support\Collection
    {
        return User::query()
            ->whereNotNull('referral_code')
            ->where('referral_program_blocked', false)
            ->get(['id', 'full_name', 'name', 'referral_code'])
            ->map(function (User $user) {
                $user->valid_referrals_count = User::validReferralsOf($user->id)->count();
                $user->total_commission_cents = ReferralCommission::where('referrer_id', $user->id)
                    ->where('is_counted', true)
                    ->sum('commission_cents');

                return $user;
            })
            ->filter(fn (User $user) => $user->valid_referrals_count > 0)
            ->sort(function (User $a, User $b) {
                if ($a->total_commission_cents !== $b->total_commission_cents) {
                    return $b->total_commission_cents <=> $a->total_commission_cents;
                }

                return $b->valid_referrals_count <=> $a->valid_referrals_count;
            })
            ->take($limit)
            ->values();
    }

    public function processPendingCommissions(): int
    {
        $count = 0;

        ReferralCommission::where('status', 'pending')
            ->where('is_counted', true)
            ->where('available_at', '<=', now())
            ->each(function (ReferralCommission $commission) use (&$count): void {
                $commission->status = 'available';
                $commission->save();
                $count++;
            });

        return $count;
    }

    public function processAutomaticPayouts(): int
    {
        $count = 0;

        User::whereNotNull('pix_key')
            ->whereNotNull('pix_key_type')
            ->where('referral_program_blocked', false)
            ->each(function (User $user) use (&$count): void {
                $availableCommissions = ReferralCommission::where('referrer_id', $user->id)
                    ->where('status', 'available')
                    ->where('is_counted', true)
                    ->whereNull('withdrawal_id')
                    ->get();

                if ($availableCommissions->isEmpty()) {
                    return;
                }

                $amountCents = $availableCommissions->sum('commission_cents');

                DB::transaction(function () use ($user, $availableCommissions, $amountCents, &$count): void {
                    $withdrawal = ReferralWithdrawal::create([
                        'user_id' => $user->id,
                        'amount_cents' => $amountCents,
                        'pix_key' => $user->pix_key,
                        'pix_key_type' => $user->pix_key_type,
                        'status' => 'completed',
                        'is_automatic' => true,
                        'processed_at' => now(),
                        'notes' => 'Pagamento automático via PIX após compensação.',
                    ]);

                    ReferralCommission::whereIn('id', $availableCommissions->pluck('id'))
                        ->update([
                            'status' => 'paid',
                            'withdrawal_id' => $withdrawal->id,
                        ]);

                    $count++;
                });
            });

        return $count;
    }

    public function updatePixAccount(User $user, string $pixKey, string $pixKeyType): void
    {
        if ($user->referral_program_blocked) {
            throw ValidationException::withMessages([
                'pix_key' => 'Programa de indicação bloqueado por violação da política anti-fraude.',
            ]);
        }

        $user->pix_key = $pixKey;
        $user->pix_key_type = $pixKeyType;
        $user->save();
    }

    private function isSelfReferral(User $referrer, User $newUser, string $ip): bool
    {
        if (! config('referrals.antifraud.self_referral_blocked', true)) {
            return false;
        }

        if ($referrer->id === $newUser->id) {
            return true;
        }

        if ($referrer->email === $newUser->email) {
            return true;
        }

        if ($referrer->referral_registration_ip && $referrer->referral_registration_ip === $ip) {
            return true;
        }

        return false;
    }

    private function isSameIpAsReferrer(User $referrer, string $ip): bool
    {
        if (! config('referrals.antifraud.same_ip_not_counted', true)) {
            return false;
        }

        return $referrer->referral_registration_ip === $ip;
    }

    private function checkSpamAndBlock(User $referrer, string $ip): void
    {
        $maxPerDay = config('referrals.antifraud.max_referrals_same_ip_per_day', 3);

        $sameIpCount = User::where('referred_by_id', $referrer->id)
            ->where('referral_registration_ip', $ip)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($sameIpCount >= $maxPerDay && config('referrals.antifraud.spam_block_permanent', true)) {
            $referrer->referral_program_blocked = true;
            $referrer->save();
        }
    }

    private function isReferralEligibleForCommission(User $referredUser): bool
    {
        if (! $referredUser->referral_is_counted) {
            return false;
        }

        if (! $referredUser->isActive()) {
            return false;
        }

        if (config('referrals.antifraud.require_active_subscription_for_commission', true)) {
            return $referredUser->subscription_status === 'active';
        }

        return true;
    }
}

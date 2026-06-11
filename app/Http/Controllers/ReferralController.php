<?php

namespace App\Http\Controllers;

use App\Models\ReferralCommission;
use App\Models\ReferralWithdrawal;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReferralController extends Controller
{
    public function __construct(
        private ReferralService $referralService
    ) {}

    public function capture(string $code)
    {
        $referrer = User::where('referral_code', strtoupper($code))->first();

        if ($referrer && ! $referrer->referral_program_blocked) {
            Session::put('referral.code', $referrer->referral_code);
        }

        return redirect()->route('onboarding.user-type')
            ->with('status', $referrer && ! $referrer->referral_program_blocked
                ? 'Indicação registrada! Crie sua conta para continuar.'
                : null);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $this->referralService->processPendingCommissions();
        $this->referralService->processAutomaticPayouts();

        $user = $this->referralService->ensureReferralCode($user);
        $stats = $this->referralService->getDashboardStats($user);
        $referralLink = $this->referralService->getReferralLink($user);

        $referrals = User::where('referred_by_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get(['id', 'full_name', 'name', 'email', 'subscription_status', 'referral_is_counted', 'referral_invalid_reason', 'created_at']);

        $commissions = ReferralCommission::where('referrer_id', $user->id)
            ->where('is_counted', true)
            ->with('referred:id,full_name,name,email')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        $withdrawals = ReferralWithdrawal::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        return view('referrals.index', [
            'user' => $user,
            'stats' => $stats,
            'referralLink' => $referralLink,
            'referrals' => $referrals,
            'commissions' => $commissions,
            'withdrawals' => $withdrawals,
        ]);
    }

    public function ranking(Request $request)
    {
        $ranking = $this->referralService->getOfficialRanking(30);
        $user = $request->user();
        $userRank = null;

        if ($user) {
            $user = $this->referralService->ensureReferralCode($user);
            $position = 1;
            foreach ($this->referralService->getOfficialRanking(500) as $entry) {
                if ($entry->id === $user->id) {
                    $userRank = $position;
                    break;
                }
                $position++;
            }
        }

        return view('referrals.ranking', [
            'ranking' => $ranking,
            'user' => $user,
            'userRank' => $userRank,
        ]);
    }

    public function updateCode(Request $request)
    {
        $data = $request->validate([
            'referral_code' => ['required', 'string', 'max:16'],
        ]);

        $this->referralService->updateCustomCode($request->user(), $data['referral_code']);

        return redirect()->route('referrals.index')->with('status', 'Código personalizado atualizado com sucesso!');
    }

    public function updatePix(Request $request)
    {
        $data = $request->validate([
            'pix_key' => ['required', 'string', 'max:255'],
            'pix_key_type' => ['required', 'in:'.implode(',', array_keys(config('referrals.pix_key_types')))],
        ]);

        $this->referralService->updatePixAccount(
            $request->user(),
            $data['pix_key'],
            $data['pix_key_type']
        );

        $this->referralService->processAutomaticPayouts();

        return redirect()->route('referrals.index')->with('status', 'Chave PIX salva. Recebimentos automáticos ativados.');
    }
}

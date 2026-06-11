<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\PlanPrice;
use App\Models\ReferralCommission;
use App\Models\ReferralWithdrawal;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();

        $stats = [
            'total_users' => User::count(),
            'players' => User::where('role', 'player')->count(),
            'scouts' => User::where('role', 'scout')->count(),
            'active_subscriptions' => User::where('subscription_status', 'active')->count(),
            'canceled_subscriptions' => User::where('subscription_status', 'canceled')->count(),
            'inactive_accounts' => User::where('is_active', false)->count(),
            'new_users_month' => User::where('created_at', '>=', $startOfMonth)->count(),
            'conversations' => Conversation::count(),
            'messages' => Message::count(),
            'favorites' => Favorite::count(),
            'total_referrals' => User::whereNotNull('referred_by_id')->count(),
            'valid_referrals' => User::whereNotNull('referred_by_id')->where('referral_is_counted', true)->count(),
            'blocked_affiliates' => User::where('referral_program_blocked', true)->count(),
            'commissions_total_cents' => ReferralCommission::where('is_counted', true)->sum('commission_cents'),
            'withdrawals_total_cents' => ReferralWithdrawal::where('status', 'completed')->sum('amount_cents'),
            'pending_commissions_cents' => ReferralCommission::where('status', 'pending')->where('is_counted', true)->sum('commission_cents'),
        ];

        $planGroups = User::whereNotNull('plan_group')
            ->select('plan_group', DB::raw('count(*) as total'))
            ->groupBy('plan_group')
            ->pluck('total', 'plan_group');

        $subscriptionsByInterval = User::where('subscription_status', 'active')
            ->whereNotNull('plan_interval')
            ->select('plan_interval', DB::raw('count(*) as total'))
            ->groupBy('plan_interval')
            ->pluck('total', 'plan_interval');

        $signupsByDay = User::where('created_at', '>=', $now->copy()->subDays(13)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $recentUsers = User::latest()->take(8)->get();
        $recentCommissions = ReferralCommission::with(['referrer:id,full_name,name,email', 'referred:id,full_name,name'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'planGroups',
            'subscriptionsByInterval',
            'signupsByDay',
            'recentUsers',
            'recentCommissions'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query()->withCount(['playerProfile', 'scoutProfile', 'referrals']);

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        if ($role = $request->string('role')->toString()) {
            $query->where('role', $role);
        }

        if ($planGroup = $request->string('plan_group')->toString()) {
            $query->where('plan_group', $planGroup);
        }

        $subscription = $request->string('subscription')->toString();
        if ($subscription === 'active') {
            $query->where('subscription_status', 'active');
        } elseif ($subscription === 'canceled') {
            $query->where('subscription_status', 'canceled');
        } elseif ($subscription === 'none') {
            $query->where(function ($q) {
                $q->whereNull('subscription_status')->orWhere('subscription_status', '');
            });
        }

        $activeFilter = $request->string('active')->toString();
        if ($activeFilter === '1') {
            $query->where('is_active', true);
        } elseif ($activeFilter === '0') {
            $query->where('is_active', false);
        }

        if ($request->boolean('affiliate_blocked')) {
            $query->where('referral_program_blocked', true);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function showUser(User $user)
    {
        $user->loadCount(['referrals', 'referralCommissionsEarned', 'referralWithdrawals', 'favoritePlayers']);
        $user->load(['referrer:id,full_name,name,email,referral_code', 'playerProfile', 'scoutProfile']);

        $referrals = User::where('referred_by_id', $user->id)->latest()->take(15)->get();
        $commissions = ReferralCommission::where('referrer_id', $user->id)->with('referred:id,full_name,name')->latest()->take(10)->get();
        $withdrawals = ReferralWithdrawal::where('user_id', $user->id)->latest()->take(10)->get();

        return view('admin.users.show', compact('user', 'referrals', 'commissions', 'withdrawals'));
    }

    public function toggleAdmin(User $user, Request $request)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['admin' => 'Você não pode remover seu próprio acesso admin aqui.']);
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        $msg = $user->is_admin ? 'Usuário promovido a administrador.' : 'Privilégios de admin removidos.';

        return back()->with('status', $msg);
    }

    public function blockReferralProgram(User $user, Request $request)
    {
        if ($user->is_admin) {
            return back()->withErrors(['admin' => 'Não é possível bloquear programa de outro administrador.']);
        }

        $user->update(['referral_program_blocked' => true]);

        return back()->with('status', 'Programa de indicação bloqueado para este usuário.');
    }

    public function unblockReferralProgram(User $user)
    {
        $user->update(['referral_program_blocked' => false]);

        return back()->with('status', 'Programa de indicação desbloqueado.');
    }

    public function invalidateReferral(User $user)
    {
        if (! $user->referred_by_id) {
            return back()->withErrors(['referral' => 'Este usuário não foi indicado por ninguém.']);
        }

        $user->update([
            'referral_is_counted' => false,
            'referral_invalid_reason' => 'Invalidado pelo administrador',
        ]);

        ReferralCommission::where('referred_id', $user->id)->update(['is_counted' => false]);

        return back()->with('status', 'Indicação marcada como inválida. Comissões futuras não serão geradas.');
    }

    public function subscriptions(Request $request)
    {
        $active = User::where('subscription_status', 'active')
            ->with(['playerProfile', 'scoutProfile'])
            ->latest('current_period_end')
            ->paginate(20, ['*'], 'active_page');

        $expiringSoon = User::where('subscription_status', 'active')
            ->whereNotNull('current_period_end')
            ->whereBetween('current_period_end', [now(), now()->addDays(7)])
            ->orderBy('current_period_end')
            ->take(10)
            ->get();

        $byPlan = User::where('subscription_status', 'active')
            ->whereNotNull('plan_group')
            ->select('plan_group', 'plan_interval', DB::raw('count(*) as total'))
            ->groupBy('plan_group', 'plan_interval')
            ->get();

        $mrrEstimateCents = $this->estimateMrrCents();
        $activeCount = User::where('subscription_status', 'active')->count();

        return view('admin.subscriptions', compact('active', 'expiringSoon', 'byPlan', 'mrrEstimateCents', 'activeCount'));
    }

    public function referrals(Request $request)
    {
        $topAffiliates = User::whereNotNull('referral_code')
            ->where('referral_program_blocked', false)
            ->get(['id', 'full_name', 'name', 'email', 'referral_code'])
            ->map(function (User $u) {
                $u->valid_referrals = User::validReferralsOf($u->id)->count();
                $u->earnings_cents = ReferralCommission::where('referrer_id', $u->id)->where('is_counted', true)->sum('commission_cents');

                return $u;
            })
            ->filter(fn ($u) => $u->valid_referrals > 0 || $u->earnings_cents > 0)
            ->sortByDesc('earnings_cents')
            ->take(20)
            ->values();

        $blockedAffiliates = User::where('referral_program_blocked', true)->latest()->paginate(15, ['*'], 'blocked_page');
        $invalidReferrals = User::whereNotNull('referred_by_id')->where('referral_is_counted', false)->latest()->paginate(15, ['*'], 'invalid_page');
        $recentCommissions = ReferralCommission::with(['referrer', 'referred'])->latest()->paginate(15);

        $stats = [
            'total_commissions' => ReferralCommission::count(),
            'counted_commissions' => ReferralCommission::where('is_counted', true)->count(),
            'total_paid_cents' => ReferralWithdrawal::where('status', 'completed')->sum('amount_cents'),
            'pending_cents' => ReferralCommission::where('status', 'pending')->where('is_counted', true)->sum('commission_cents'),
        ];

        return view('admin.referrals', compact('topAffiliates', 'blockedAffiliates', 'invalidReferrals', 'recentCommissions', 'stats'));
    }

    public function referralWithdrawals(Request $request)
    {
        $query = ReferralWithdrawal::with('user:id,full_name,name,email');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $withdrawals = $query->latest()->paginate(25)->withQueryString();

        $totals = [
            'completed' => ReferralWithdrawal::where('status', 'completed')->sum('amount_cents'),
            'pending' => ReferralWithdrawal::where('status', 'pending')->sum('amount_cents'),
            'count' => ReferralWithdrawal::count(),
        ];

        return view('admin.referrals-withdrawals', compact('withdrawals', 'totals'));
    }

    public function processReferralPayouts(ReferralService $referralService)
    {
        $released = $referralService->processPendingCommissions();
        $paid = $referralService->processAutomaticPayouts();

        return back()->with('status', "Processado: {$released} comissão(ões) liberada(s), {$paid} saque(s) automático(s).");
    }

    public function cancelPlan(User $user, Request $request)
    {
        if ($user->is_admin) {
            return back()->withErrors(['admin' => 'Não é possível cancelar o plano de outro administrador.']);
        }

        if (! $user->stripe_subscription_id) {
            $user->update([
                'subscription_status' => 'canceled',
                'plan_type' => null,
                'plan_interval' => null,
                'current_period_end' => null,
            ]);

            return back()->with('status', 'Usuário não tinha assinatura ativa no Stripe; dados locais atualizados.');
        }

        Stripe::setApiKey(config('stripe.secret'));

        try {
            $subscription = \Stripe\Subscription::retrieve($user->stripe_subscription_id);
            $subscription->cancel();
        } catch (\Throwable $e) {
            return back()->withErrors(['stripe' => 'Não foi possível cancelar no Stripe. Tente novamente.']);
        }

        $user->update([
            'subscription_status' => 'canceled',
            'current_period_end' => null,
        ]);

        return back()->with('status', 'Plano do usuário cancelado com sucesso.');
    }

    public function deactivate(User $user, Request $request)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['admin' => 'Você não pode inativar sua própria conta aqui.']);
        }

        if ($user->is_admin) {
            return back()->withErrors(['admin' => 'Não é possível inativar outro administrador.']);
        }

        $user->update(['is_active' => false]);

        return back()->with('status', 'Usuário inativado.');
    }

    public function reactivate(User $user)
    {
        $user->update(['is_active' => true]);

        return back()->with('status', 'Usuário reativado com sucesso.');
    }

    public function planPrices(Request $request)
    {
        $plans = PlanPrice::orderBy('sort_order')->get();

        return view('admin.plan-prices', compact('plans'));
    }

    public function updatePlanPrices(Request $request)
    {
        $plans = PlanPrice::orderBy('sort_order')->get();

        $rules = [];
        foreach ($plans as $plan) {
            $field = 'amount_'.$plan->id;
            if ($request->has($field)) {
                $rules[$field] = ['required', 'numeric', 'min:0', 'max:99999.99'];
            }
        }

        if (empty($rules)) {
            return back()->withErrors(['plan' => 'Nenhum preço enviado para atualização.']);
        }

        $data = $request->validate($rules);

        foreach ($plans as $plan) {
            $key = 'amount_'.$plan->id;
            if (! isset($data[$key])) {
                continue;
            }

            $plan->amount_cents = (int) round((float) $data[$key] * 100);
            $plan->display_label = 'R$ '.number_format($plan->amount_reais, 2, ',', '.');
            $plan->display_label .= $plan->interval === 'year' ? ' / ano' : ' / mês';
            $plan->save();
        }

        return redirect()->route('admin.plan-prices')->with('status', 'Preços atualizados com sucesso.');
    }

    public function togglePlanPrice(PlanPrice $plan)
    {
        $plan->update(['is_active' => ! $plan->is_active]);

        return back()->with('status', $plan->is_active ? 'Plano ativado.' : 'Plano desativado.');
    }

    private function estimateMrrCents(): int
    {
        $total = 0;

        $activeUsers = User::where('subscription_status', 'active')
            ->whereNotNull('plan_group')
            ->whereNotNull('plan_interval')
            ->get(['plan_group', 'plan_interval']);

        foreach ($activeUsers as $user) {
            $key = $user->plan_group.'_'.$user->plan_interval;
            $plan = PlanPrice::where('plan_key', $key)->where('is_active', true)->first();
            if ($plan) {
                $total += $plan->interval === 'year'
                    ? (int) round($plan->amount_cents / 12)
                    : $plan->amount_cents;
            }
        }

        return $total;
    }
}

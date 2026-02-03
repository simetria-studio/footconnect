<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\PlanPrice;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Stripe;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalUsers = User::count();
        $players = User::where('role', 'player')->count();
        $scouts = User::where('role', 'scout')->count();
        $conversations = Conversation::count();
        $messages = Message::count();

        $recentUsers = User::latest()->take(10)->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'players' => $players,
            'scouts' => $scouts,
            'conversations' => $conversations,
            'messages' => $messages,
            'recentUsers' => $recentUsers,
        ]);
    }

    public function users(Request $request)
    {
        $query = User::query()->withCount(['playerProfile', 'scoutProfile']);

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->string('role')->toString()) {
            $query->where('role', $role);
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

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users', [
            'users' => $users,
        ]);
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

        return back()->with('status', 'Usuário inativado. Ele será deslogado ao acessar o app.');
    }

    public function reactivate(User $user, Request $request)
    {
        $user->update(['is_active' => true]);

        return back()->with('status', 'Usuário reativado com sucesso.');
    }

    public function planPrices(Request $request)
    {
        $plans = PlanPrice::orderBy('sort_order')->get();

        return view('admin.plan-prices', [
            'plans' => $plans,
        ]);
    }

    public function updatePlanPrices(Request $request)
    {
        $plans = PlanPrice::orderBy('sort_order')->get();

        $rules = [];
        foreach ($plans as $plan) {
            $rules['amount_'.$plan->id] = ['required', 'numeric', 'min:0', 'max:99999.99'];
        }

        $data = $request->validate($rules);

        foreach ($plans as $plan) {
            $key = 'amount_'.$plan->id;
            if (isset($data[$key])) {
                $plan->amount_cents = (int) round((float) $data[$key] * 100);
                $plan->display_label = 'R$ '.number_format($plan->amount_reais, 2, ',', '.');
                if ($plan->interval === 'month' && $plan->interval_count > 1) {
                    $plan->display_label .= ' / '.$plan->interval_count.' meses';
                } elseif ($plan->interval === 'month') {
                    $plan->display_label .= ' / mês';
                } else {
                    $plan->display_label .= ' / ano';
                }
                $plan->save();
            }
        }

        return redirect()->route('admin.plan-prices')->with('status', 'Preços dos planos atualizados com sucesso.');
    }
}

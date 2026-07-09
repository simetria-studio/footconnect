<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user && ! $user->isAdmin()) {
                $hasActiveSubscription = $user->subscription_status === 'active'
                    && (! $user->current_period_end || $user->current_period_end->isFuture());

                if (! $hasActiveSubscription) {
                    if ($user->plan_group && config('plans.groups.'.$user->plan_group)) {
                        return redirect()->route('onboarding.plans');
                    }

                    return redirect()->route('onboarding.user-type');
                }
            }

            return redirect()->intended('/home');
        }

        return back()
            ->withErrors(['email' => 'Credenciais inválidas.'])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        $role = request('role', session('onboarding.role', 'player'));

        return view('auth.register', [
            'role' => $role,
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', 'in:player,scout'],
            'checkout_session_id' => ['nullable', 'string'],
        ]);

        // Extrai o nome do email (parte antes do @) ou usa um valor padrão
        $name = explode('@', $data['email'])[0];
        $name = ucfirst($name); // Primeira letra maiúscula

        $user = User::create([
            'name' => $name, // Campo obrigatório
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        // Vincula assinatura Stripe, se houver sessão de checkout
        if (! empty($data['checkout_session_id']) && config('stripe.secret')) {
            try {
                \Stripe\Stripe::setApiKey(config('stripe.secret'));
                $session = \Stripe\Checkout\Session::retrieve($data['checkout_session_id']);

                if ($session->customer && $session->subscription) {
                    $user->stripe_customer_id = $session->customer;
                    $user->stripe_subscription_id = $session->subscription;
                    $planKey = $session->metadata->plan_key ?? null;
                    $planGroup = $session->metadata->plan_group ?? null;

                    $user->plan_group = $planGroup;
                    $user->plan_type = $planGroup ?? ($data['role'] === 'player' ? 'g1' : 'g3');
                    $user->plan_interval = str_ends_with((string) $planKey, '_yearly') ? 'yearly' : 'monthly';

                    // Opcionalmente, marca como ativa até o webhook atualizar
                    $user->subscription_status = 'active';
                    $user->save();
                }
            } catch (\Throwable $e) {
                // Em caso de falha, segue sem travar cadastro
            }
        }

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}


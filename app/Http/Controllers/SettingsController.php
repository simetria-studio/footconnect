<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;

class SettingsController extends Controller
{
    public function profile(Request $request)
    {
        return view('settings.profile', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'full_name' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
        ]);

        $user->fill($data);
        $user->save();

        return redirect()->route('settings.profile')->with('status', 'Perfil atualizado com sucesso.');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->route('settings.profile')->with('status', 'Senha alterada com sucesso.');
    }

    public function plan(Request $request)
    {
        return view('settings.plan', [
            'user' => $request->user(),
        ]);
    }

    public function cancelPlan(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user->stripe_subscription_id) {
            return back()->withErrors(['plan' => 'Nenhuma assinatura ativa para cancelar.']);
        }

        Stripe::setApiKey(config('stripe.secret'));

        try {
            $subscription = \Stripe\Subscription::retrieve($user->stripe_subscription_id);
            $subscription->cancel();
        } catch (\Throwable $e) {
            return back()->withErrors(['plan' => 'Não foi possível cancelar a assinatura agora. Tente novamente.']);
        }

        $user->subscription_status = 'canceled';
        $user->save();

        return redirect()->route('settings.plan')->with('status', 'Assinatura cancelada com sucesso.');
    }
}


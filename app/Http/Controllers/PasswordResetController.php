<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Enviamos um link de redefinição para o seu e-mail.');
        }

        return back()->withErrors([
            'email' => $this->translateStatus($status),
        ])->onlyInput('email');
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('status', 'Senha redefinida com sucesso! Faça login com a nova senha.');
        }

        return back()->withErrors([
            'email' => $this->translateStatus($status),
        ])->onlyInput('email');
    }

    private function translateStatus(string $status): string
    {
        return match ($status) {
            Password::INVALID_USER => 'Não encontramos uma conta com este e-mail.',
            Password::INVALID_TOKEN => 'Este link de redefinição é inválido ou expirou. Solicite um novo.',
            Password::RESET_THROTTLED => 'Aguarde alguns minutos antes de solicitar outro link.',
            default => 'Não foi possível processar sua solicitação. Tente novamente.',
        };
    }
}

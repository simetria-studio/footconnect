<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject('Redefinir senha — FootConnect')
            ->greeting('Olá!')
            ->line('Você está recebendo este e-mail porque solicitou a redefinição de senha da sua conta FootConnect.')
            ->action('Redefinir senha', $url)
            ->line("Este link expira em {$expire} minutos.")
            ->line('Se você não solicitou a redefinição, ignore este e-mail. Sua senha permanecerá a mesma.');
    }
}

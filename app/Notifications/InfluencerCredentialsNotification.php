<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InfluencerCredentialsNotification extends Notification
{
    public function __construct(
        public string $plainPassword,
        public string $referralLink,
        public string $referralCode,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $notifiable->full_name ?: $notifiable->name;

        return (new MailMessage)
            ->subject('Seu acesso de influenciador — FootConnect')
            ->greeting('Olá, '.$name.'!')
            ->line('Você foi cadastrado como influenciador da FootConnect. Use os dados abaixo para acessar a plataforma e divulgar seu link.')
            ->line('Login (e-mail): '.$notifiable->email)
            ->line('Senha: '.$this->plainPassword)
            ->line('Seu código: '.$this->referralCode)
            ->action('Acessar a FootConnect', url('/login'))
            ->line('Seu link de indicação: '.$this->referralLink)
            ->line('Recomendamos alterar a senha no primeiro acesso, em Configurações.');
    }
}

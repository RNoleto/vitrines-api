<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = 'https://vitrines.vercel.app';

        return (new MailMessage)
        ->subject('Bem-vindo ao nosso sistema!')
        ->greeting("Olá {$notifiable->name}!")
        ->line('Seu cadastro foi realizado com sucesso.')
        ->line('Aproveite todos os recursos disponíveis.')
        ->action('Acessar a Plataforma Vitrines', $frontendUrl)
        ->line('Obrigado por se juntar a nós!')
        ->salutation('Atenciosamente, Equipe Vitrines')
        ->with([
            'customFooter' => "Se você estiver com problemas para clicar no botão, copie e cole esta URL no seu navegador: $frontendUrl"
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

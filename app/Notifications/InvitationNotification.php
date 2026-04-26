<?php

namespace App\Notifications;

use App\Http\Controllers\EmailUnsubscribeController;
use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationNotification extends Notification
{
    use Queueable;

    public function __construct(public Invitation $invitation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $orgName = $this->invitation->organization?->name ?? config('app.name');
        $senderName = $this->invitation->invitedBy?->name ?? 'Un administrateur';
        $acceptUrl = route('invitation.accept', $this->invitation->token);

        return (new MailMessage)
            ->subject("Invitation à rejoindre {$orgName}")
            ->greeting('Bonjour,')
            ->line("**{$senderName}** vous invite à rejoindre l'espace **{$orgName}** sur " . config('app.name') . '.')
            ->line("Cliquez sur le bouton ci-dessous pour accepter l'invitation :")
            ->action('Accepter l\'invitation', $acceptUrl)
            ->line("Vous pouvez aussi utiliser ce code d'invitation : **{$this->invitation->code}**")
            ->line('Ce code est valable 7 jours.')
            ->salutation('— ' . config('app.name'))
            ->line('[Se desabonner](' . route('email.unsubscribe', EmailUnsubscribeController::generateToken($this->invitation->email)) . ')');
    }
}

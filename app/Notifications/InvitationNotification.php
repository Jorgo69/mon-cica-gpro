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
        $senderName = $this->invitation->invitedBy?->name ?? __('mail.an_admin');
        $acceptUrl = route('invitation.accept', $this->invitation->token);

        return (new MailMessage)
            ->subject(__('mail.invitation.subject', ['organization' => $orgName]))
            ->greeting(__('mail.greeting_simple'))
            ->line(__('mail.invitation.line1', ['sender' => $senderName, 'organization' => $orgName, 'app' => config('app.name')]))
            ->line(__('mail.invitation.line2'))
            ->action(__('mail.invitation.action'), $acceptUrl)
            ->line(__('mail.invitation.line3', ['code' => $this->invitation->code]))
            ->line(__('mail.invitation.line4'))
            ->salutation(__('mail.salutation', ['app' => config('app.name')]))
            ->line('[' . __('mail.unsubscribe') . '](' . route('email.unsubscribe', EmailUnsubscribeController::generateToken($this->invitation->email)) . ')');
    }
}

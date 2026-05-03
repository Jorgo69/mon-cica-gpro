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
        $org = $this->invitation->organization;
        $orgName = $org?->name ?? config('app.name');
        $sender = $this->invitation->invitedBy;
        $senderName = $sender?->name ?? __('mail.an_admin');
        $senderRole = $sender?->role?->label() ?? null;
        $acceptUrl = route('invitation.accept', $this->invitation->token);

        // Role mapping for display
        $roleLabels = [
            'ORG_ADMIN' => __('admin.invitations.roles.org_admin'),
            'MANAGER' => __('admin.invitations.roles.manager'),
            'MEMBER' => __('admin.invitations.roles.member'),
            'SUPERVISOR' => __('admin.invitations.roles.supervisor'),
        ];
        $roleKeys = [
            'ORG_ADMIN' => 'admin',
            'MANAGER' => 'manager',
            'MEMBER' => 'member',
            'SUPERVISOR' => 'manager',
        ];

        $unsubscribeUrl = null;
        try {
            $unsubscribeUrl = route('email.unsubscribe', EmailUnsubscribeController::generateToken($this->invitation->email));
        } catch (\Exception $e) {
            // Route might not exist in tests
        }

        return (new MailMessage)
            ->subject(__('mail.invitation.subject', ['organization' => $orgName]))
            ->view('mail.invitation', [
                'subject' => __('mail.invitation.subject', ['organization' => $orgName]),
                'orgName' => $orgName,
                'orgLogoUrl' => $org?->logo_url,
                'orgWebsite' => $org?->website,
                'orgContactEmail' => $org?->contact_email,
                'orgContactPhone' => $org?->contact_phone,
                'senderName' => $senderName,
                'senderRole' => $senderRole,
                'roleLabel' => $roleLabels[$this->invitation->spatie_role] ?? $this->invitation->spatie_role,
                'roleKey' => $roleKeys[$this->invitation->spatie_role] ?? 'member',
                'acceptUrl' => $acceptUrl,
                'code' => $this->invitation->code,
                'unsubscribeUrl' => $unsubscribeUrl,
            ]);
    }
}

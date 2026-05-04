<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrgDeletionScheduledNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Organization $organization
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $deletionDate = now()->addDays(30)->format('d/m/Y');

        return (new MailMessage)
            ->subject(__('mail.org_deletion.subject', ['org' => $this->organization->name]))
            ->greeting(__('mail.org_deletion.greeting'))
            ->line(__('mail.org_deletion.line1', ['org' => $this->organization->name]))
            ->line(__('mail.org_deletion.line2', ['date' => $deletionDate]))
            ->line(__('mail.org_deletion.line3'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'org_deletion_scheduled',
            'organization_id' => $this->organization->id,
            'organization_name' => $this->organization->name,
            'deletion_date' => now()->addDays(30)->toDateString(),
        ];
    }
}

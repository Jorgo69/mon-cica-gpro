<?php

namespace App\Notifications;

use App\Models\Indicator;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IndicatorAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Indicator $indicator,
        protected string $alertType, // 'stagnation' or 'regression'
        protected string $projectTitle,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->alertType === 'regression'
            ? __('indicators.alert_regression_subject', ['indicator' => Str::limit($this->indicator->description, 40)])
            : __('indicators.alert_stagnation_subject', ['indicator' => Str::limit($this->indicator->description, 40)]);

        return (new MailMessage)
            ->subject($subject)
            ->greeting(__('mail.greeting', ['name' => $notifiable->name]))
            ->line($this->alertType === 'regression'
                ? __('indicators.alert_regression_body', [
                    'indicator' => $this->indicator->description,
                    'project' => $this->projectTitle,
                    'current' => $this->indicator->current_value,
                ])
                : __('indicators.alert_stagnation_body', [
                    'indicator' => $this->indicator->description,
                    'project' => $this->projectTitle,
                ])
            )
            ->action(__('common.view'), url('/'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'indicator_alert',
            'alert_type' => $this->alertType,
            'indicator_id' => $this->indicator->id,
            'indicator_description' => $this->indicator->description,
            'project_title' => $this->projectTitle,
            'current_value' => $this->indicator->current_value,
            'target_value' => $this->indicator->target_value,
        ];
    }
}

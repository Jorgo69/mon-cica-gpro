<?php

namespace App\Livewire\VBeta\Settings;

use App\Services\UserMeta;
use App\Livewire\Traits\WithToastNotifications;
use Livewire\Component;

class SettingsLivewire extends Component
{
    use WithToastNotifications;

    public string $theme = 'light';
    public string $locale = 'fr';
    public string $density = 'comfortable';
    public string $dateFormat = 'fr';
    public string $timezone = 'Europe/Paris';
    public bool $emailNotifications = true;
    public bool $pushNotifications = false;
    public string $digestFrequency = 'weekly';
    public string $profileVisibility = 'private';

    public function mount()
    {
        $this->theme = UserMeta::get('theme', 'light');
        $this->locale = UserMeta::get('locale', app()->getLocale());
        $this->density = UserMeta::get('density', 'comfortable');
        $this->dateFormat = UserMeta::get('date_format', 'fr');
        $this->timezone = UserMeta::get('timezone', 'Europe/Paris');
        $this->emailNotifications = UserMeta::get('notifications.email', true);
        $this->pushNotifications = UserMeta::get('notifications.push', false);
        $this->digestFrequency = UserMeta::get('notifications.digest', 'weekly');
        $this->profileVisibility = UserMeta::get('privacy.visibility', 'private');
    }

    public function saveTheme(string $value)
    {
        $this->theme = $value;
        UserMeta::set('theme', $value);
    }

    public function saveDensity(string $value)
    {
        $this->density = $value;
        UserMeta::set('density', $value);
    }

    public function saveLocale(string $value)
    {
        if (!in_array($value, ['fr', 'en'])) {
            return;
        }

        $this->locale = $value;
        UserMeta::set('locale', $value);
        session(['locale' => $value]);

        return $this->redirect(route('setting'), navigate: false);
    }

    public function saveDateFormat(string $value)
    {
        $this->dateFormat = $value;
        UserMeta::set('date_format', $value);
    }

    public function saveTimezone(string $value)
    {
        $this->timezone = $value;
        UserMeta::set('timezone', $value);
    }

    public function saveEmailNotifications(bool $value)
    {
        $this->emailNotifications = $value;
        UserMeta::set('notifications.email', $value);
    }

    public function savePushNotifications(bool $value)
    {
        $this->pushNotifications = $value;
        UserMeta::set('notifications.push', $value);
    }

    public function saveDigestFrequency(string $value)
    {
        $this->digestFrequency = $value;
        UserMeta::set('notifications.digest', $value);
    }

    public function saveProfileVisibility(string $value)
    {
        $this->profileVisibility = $value;
        UserMeta::set('privacy.visibility', $value);
    }

    public function render()
    {
        return view('livewire.v-beta.settings.settings-livewire');
    }
}

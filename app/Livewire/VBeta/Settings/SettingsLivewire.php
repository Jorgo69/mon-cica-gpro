<?php

namespace App\Livewire\VBeta\Settings;

use App\Services\UserMeta;
use App\Livewire\Traits\WithToastNotifications;
use Livewire\Component;

class SettingsLivewire extends Component
{
    use WithToastNotifications;

    public string $activeTab = 'appearance';
    public string $theme = 'light';
    public string $locale = 'fr';
    public string $density = 'comfortable';
    public string $dateFormat = 'dd/MM/yyyy';
    public bool $emailNotifications = true;
    public string $digestFrequency = 'weekly';

    public function mount()
    {
        $defaults = config('gpro.defaults');
        $this->theme = UserMeta::get('theme', $defaults['theme'] ?? 'light');
        $this->locale = UserMeta::get('locale', $defaults['locale'] ?? 'fr');
        $this->density = UserMeta::get('density', $defaults['density'] ?? 'comfortable');
        $this->dateFormat = UserMeta::get('date_format', 'dd/MM/yyyy');
        $this->emailNotifications = (bool) UserMeta::get('notifications.email', true);
        $this->digestFrequency = UserMeta::get('notifications.digest', 'weekly');
    }

    #[\Livewire\Attributes\On('navbar-theme-changed')]
    public function onNavbarThemeChanged($theme)
    {
        $this->theme = $theme;
    }

    public function updatedTheme($value)
    {
        UserMeta::set('theme', $value);
        $this->dispatch('theme-changed', theme: $value);
        $this->notifyToast('success', 'Theme mis a jour.');
    }

    public function updatedDensity($value)
    {
        UserMeta::set('density', $value);
        $this->notifyToast('success', 'Densite mise a jour.');
    }

    public function updatedLocale($value)
    {
        if (!in_array($value, ['fr', 'en'])) return;

        UserMeta::set('locale', $value);
        session(['locale' => $value]);
        $this->notifyToast('success', 'Langue mise a jour.');
        return $this->redirect(route('setting'), navigate: false);
    }

    public function updatedDateFormat($value)
    {
        UserMeta::set('date_format', $value);
        $this->notifyToast('success', 'Format de date mis a jour.');
    }

    public function updatedEmailNotifications($value)
    {
        UserMeta::set('notifications.email', $value);
        $this->notifyToast('success', 'Notifications email ' . ($value ? 'activees' : 'desactivees') . '.');
    }

    public function updatedDigestFrequency($value)
    {
        UserMeta::set('notifications.digest', $value);
        $labels = ['never' => 'desactive', 'weekly' => 'hebdomadaire', 'monthly' => 'mensuel'];
        $this->notifyToast('success', 'Resume ' . ($labels[$value] ?? $value) . '.');
    }

    public function render()
    {
        return view('livewire.v-beta.settings.settings-livewire');
    }
}

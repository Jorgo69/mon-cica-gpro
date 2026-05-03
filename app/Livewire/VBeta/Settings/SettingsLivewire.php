<?php

namespace App\Livewire\VBeta\Settings;

use App\Enums\AccountType;
use App\Enums\AiProvider;
use App\Enums\NotificationType;
use App\Models\AiConfig;
use App\Models\Organization;
use App\Services\NotificationPreferenceService;
use App\Services\UserMeta;
use App\Livewire\Traits\WithToastNotifications;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingsLivewire extends Component
{
    use WithToastNotifications, WithFileUploads;

    public string $activeTab = 'appearance';
    public string $theme = 'light';
    public string $locale = 'fr';
    public string $density = 'comfortable';
    public string $dateFormat = 'dd/MM/yyyy';
    public bool $emailNotifications = true;
    public string $digestFrequency = 'weekly';
    public string $timezone = 'Africa/Porto-Novo';

    public function mount()
    {
        $defaults = config('gpro.defaults');
        $this->theme = UserMeta::get('theme', $defaults['theme'] ?? 'light');
        $this->locale = UserMeta::get('locale', $defaults['locale'] ?? 'fr');
        $this->density = UserMeta::get('density', $defaults['density'] ?? 'comfortable');
        $this->dateFormat = UserMeta::get('date_format', 'dd/MM/yyyy');
        $this->emailNotifications = (bool) UserMeta::get('notifications.email', true);
        $this->digestFrequency = UserMeta::get('notifications.digest', 'weekly');
        $this->timezone = UserMeta::get('timezone', config('gpro.defaults.timezone', 'Africa/Porto-Novo'));

        if (!UserMeta::get('visited_settings')) {
            UserMeta::set('visited_settings', true);
        }

        $this->loadAiConfig();
        $this->loadOrgProfile();
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

    public function updatedTimezone($value)
    {
        $valid = array_keys(config('gpro.timezones', []));
        if (!in_array($value, $valid)) return;

        UserMeta::set('timezone', $value);
        $this->notifyToast('success', 'Fuseau horaire mis a jour.');
    }

    public function toggleNotificationChannel(string $type, string $channel)
    {
        $user = auth()->user();
        $notifType = NotificationType::tryFrom($type);
        if (!$notifType) return;

        $current = $user->getMeta("notifications.preferences.{$type}") ?? $notifType->defaultChannels();

        if (in_array($channel, $current)) {
            $current = array_values(array_diff($current, [$channel]));
        } else {
            $current[] = $channel;
        }

        $user->setNotificationPreference($notifType, $current);
        $this->notifyToast('success', 'Preference mise a jour.');
    }

    public function unlinkSocial(string $provider)
    {
        $user = auth()->user();
        $account = $user->socialAccounts()->where('provider', $provider)->first();

        if (!$account) return;

        $account->delete();
        $this->notifyToast('success', ucfirst($provider) . ' delie.');
    }

    // ─── Organization Profile (ORG_ADMIN only) ─────────────

    public $orgLogo;  // Livewire file upload
    public string $orgName = '';
    public string $orgDescription = '';
    public string $orgWebsite = '';
    public string $orgContactEmail = '';
    public string $orgContactPhone = '';
    public ?string $orgLogoUrl = null;

    public function loadOrgProfile()
    {
        $user = auth()->user();
        if ($user->role !== AccountType::ORG_ADMIN || !$user->organization_id) return;

        $org = $user->organization;
        if (!$org) return;

        $this->orgName = $org->name ?? '';
        $this->orgDescription = $org->description ?? '';
        $this->orgWebsite = $org->website ?? '';
        $this->orgContactEmail = $org->contact_email ?? '';
        $this->orgContactPhone = $org->contact_phone ?? '';
        $this->orgLogoUrl = $org->logo_url;
    }

    public function saveOrgProfile()
    {
        $user = auth()->user();
        if ($user->role !== AccountType::ORG_ADMIN || !$user->organization_id) return;

        $this->validate([
            'orgName' => 'required|string|max:255',
            'orgDescription' => 'nullable|string|max:1000',
            'orgWebsite' => 'nullable|url|max:255',
            'orgContactEmail' => 'nullable|email|max:255',
            'orgContactPhone' => 'nullable|string|max:30',
            'orgLogo' => 'nullable|image|max:2048',
        ]);

        $org = Organization::findOrFail($user->organization_id);

        $data = [
            'name' => $this->orgName,
            'description' => $this->orgDescription,
            'website' => $this->orgWebsite ?: null,
            'contact_email' => $this->orgContactEmail ?: null,
            'contact_phone' => $this->orgContactPhone ?: null,
        ];

        // Handle logo upload
        if ($this->orgLogo) {
            // Delete old logo
            if ($org->logo_path) {
                Storage::disk('public')->delete($org->logo_path);
            }
            $data['logo_path'] = $this->orgLogo->store('org-logos', 'public');
            $this->orgLogo = null;
        }

        $org->update($data);
        $this->orgLogoUrl = $org->fresh()->logo_url;
        $this->notifyToast('success', __('common.saved'));
    }

    public function removeOrgLogo()
    {
        $user = auth()->user();
        if ($user->role !== AccountType::ORG_ADMIN || !$user->organization_id) return;

        $org = Organization::findOrFail($user->organization_id);
        if ($org->logo_path) {
            Storage::disk('public')->delete($org->logo_path);
            $org->update(['logo_path' => null]);
        }
        $this->orgLogoUrl = null;
        $this->notifyToast('success', __('common.saved'));
    }

    // ─── AI Configuration (ORG_ADMIN + INDEPENDENT) ─────────

    public string $aiMode = 'global'; // global, own, disabled
    public string $aiProvider = 'groq';
    public string $aiApiKey = '';
    public string $aiBaseUrl = '';
    public string $aiModel = '';
    public ?string $aiMaskedKey = null;

    public function loadAiConfig()
    {
        $user = auth()->user();

        if ($user->role === AccountType::INDEPENDENT) {
            $config = $user->aiConfig;
        } elseif ($user->role === AccountType::ORG_ADMIN && $user->organization_id) {
            $config = AiConfig::where('configurable_type', Organization::class)
                ->where('configurable_id', $user->organization_id)
                ->first();
        } else {
            return;
        }

        if (!$config) {
            $this->aiMode = 'global';
            return;
        }

        if (!$config->enabled) {
            $this->aiMode = 'disabled';
            return;
        }

        if ($config->isUsable()) {
            $this->aiMode = 'own';
            $this->aiProvider = $config->provider->value;
            $this->aiBaseUrl = $config->base_url ?? '';
            $this->aiModel = $config->model ?? '';
            $this->aiMaskedKey = $config->masked_key;
        } else {
            $this->aiMode = 'global';
        }
    }

    public function updatedAiProvider()
    {
        $provider = AiProvider::tryFrom($this->aiProvider);
        if ($provider) {
            $this->aiModel = $provider->defaultModel();
        }
    }

    public function saveAiConfig()
    {
        $user = auth()->user();

        if ($user->role === AccountType::INDEPENDENT) {
            $configurableType = \App\Models\User::class;
            $configurableId = $user->id;
        } elseif ($user->role === AccountType::ORG_ADMIN && $user->organization_id) {
            $configurableType = Organization::class;
            $configurableId = $user->organization_id;
        } else {
            return;
        }

        if ($this->aiMode === 'disabled') {
            AiConfig::updateOrCreate(
                ['configurable_type' => $configurableType, 'configurable_id' => $configurableId],
                ['provider' => 'groq', 'enabled' => false]
            );
            $this->notifyToast('success', __('common.saved'));
            return;
        }

        if ($this->aiMode === 'global') {
            AiConfig::where('configurable_type', $configurableType)
                ->where('configurable_id', $configurableId)
                ->delete();
            $this->aiMaskedKey = null;
            $this->notifyToast('success', __('common.saved'));
            return;
        }

        // mode = own
        $data = [
            'provider' => $this->aiProvider,
            'base_url' => $this->aiBaseUrl ?: null,
            'model' => $this->aiModel ?: null,
            'enabled' => true,
        ];

        if (!empty($this->aiApiKey)) {
            $data['api_key_encrypted'] = encrypt($this->aiApiKey);
        }

        $config = AiConfig::updateOrCreate(
            ['configurable_type' => $configurableType, 'configurable_id' => $configurableId],
            $data
        );

        $this->aiMaskedKey = $config->masked_key;
        $this->aiApiKey = '';
        $this->notifyToast('success', __('common.saved'));
    }

    public function toggleMemberAi(string $userId)
    {
        $user = auth()->user();
        if ($user->role !== AccountType::ORG_ADMIN) return;

        $member = \App\Models\User::where('id', $userId)
            ->where('organization_id', $user->organization_id)
            ->first();

        if (!$member) return;

        $current = (bool) ($member->getMeta('ai.disabled_by_admin') ?? false);
        $member->setMeta('ai.disabled_by_admin', !$current);
        $member->save();

        $this->notifyToast('success', __('common.saved'));
    }

    public function render()
    {
        $user = auth()->user();

        $showAiTab = in_array($user->role, [AccountType::ORG_ADMIN, AccountType::INDEPENDENT]);
        $orgMembers = collect();
        if ($user->role === AccountType::ORG_ADMIN && $user->organization_id) {
            $orgMembers = \App\Models\User::where('organization_id', $user->organization_id)
                ->where('id', '!=', $user->id)
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'meta']);
        }

        return view('livewire.v-beta.settings.settings-livewire', [
            'socialAccounts' => auth()->user()->socialAccounts ?? collect(),
            'notificationTypes' => NotificationType::userConfigurable(),
            'notificationPreferences' => NotificationPreferenceService::getAll(auth()->user()),
            'showAiTab' => $showAiTab,
            'aiProviders' => AiProvider::cases(),
            'orgMembers' => $orgMembers,
        ]);
    }
}

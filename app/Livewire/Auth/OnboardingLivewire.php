<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\CreateOrganizationAction;
use App\Enums\AccountType;
use App\Livewire\Traits\WithToastNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class OnboardingLivewire extends Component
{
    use WithToastNotifications;

    public $step = 'choice'; // choice, create, join, independent
    public $organizationName = '';
    public $inviteCode = '';

    public function selectCreate()
    {
        $this->step = 'create';
    }

    public function selectJoin()
    {
        $this->step = 'join';
    }

    public function back()
    {
        $this->step = 'choice';
        $this->resetValidation();
    }

    public function createOrganization(CreateOrganizationAction $action)
    {
        Log::info('[Onboarding] createOrganization', ['user_id' => auth()->id()]);

        $this->validate([
            'organizationName' => 'required|string|min:3|max:100|unique:organizations,name',
        ], [
            'organizationName.required' => 'Le nom de votre organisation est obligatoire.',
            'organizationName.unique'   => 'Ce nom d\'organisation est déjà utilisé.',
        ]);

        try {
            $organization = $action->execute(auth()->user(), $this->organizationName);

            $this->notifyToastSession('success', 'Votre espace de travail a été créé avec succès !', 'Bienvenue chez ' . $organization->name);

            return redirect()->route('dashboard');
        } catch (\Throwable $e) {
            Log::error('[Onboarding] createOrganization ÉCHEC', [
                'error' => $e->getMessage(),
            ]);
            $this->notifyToast('error', 'Une erreur est survenue lors de la création. Veuillez réessayer.', 'Erreur');
        }
    }

    public function selectIndependent()
    {
        Log::info('[Onboarding] selectIndependent', ['user_id' => auth()->id()]);

        try {
            DB::transaction(function () {
                auth()->user()->update([
                    'account_type' => AccountType::INDEPENDENT,
                ]);
            });

            $this->notifyToastSession('success', 'Configuration terminée', 'Bienvenue dans votre espace indépendant !');

            return redirect()->route('dashboard');
        } catch (\Throwable $e) {
            Log::error('[Onboarding] selectIndependent ÉCHEC', ['error' => $e->getMessage()]);
            $this->notifyToast('error', 'Une erreur est survenue. Veuillez réessayer.', 'Erreur');
        }
    }

    public function joinOrganization()
    {
        $this->notifyToast('warning', 'Le système d\'invitation par code arrive très prochainement.', 'Bientôt disponible');
    }

    public function render()
    {
        return view('livewire.auth.onboarding-livewire')
            ->layout('layouts.guest');
    }
}

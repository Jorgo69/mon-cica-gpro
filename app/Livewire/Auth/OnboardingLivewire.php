<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\CreateOrganizationAction;
use App\Livewire\Traits\WithToastNotifications;
use Livewire\Component;

class OnboardingLivewire extends Component
{
    use WithToastNotifications;

    public $step = 'choice'; // choice, create, join
    public $organizationName = '';
    public $inviteCode = '';

    /**
     * Passer à l'étape de création.
     */
    public function selectCreate()
    {
        $this->step = 'create';
    }

    /**
     * Passer à l'étape de jonction.
     */
    public function selectJoin()
    {
        $this->step = 'join';
    }

    /**
     * Revenir au choix initial.
     */
    public function back()
    {
        $this->step = 'choice';
        $this->resetValidation();
    }

    /**
     * Action : Créer une organisation.
     */
    public function createOrganization(CreateOrganizationAction $action)
    {
        $this->validate([
            'organizationName' => 'required|string|min:3|max:100|unique:organizations,name',
        ], [
            'organizationName.required' => 'Le nom de votre organisation est obligatoire.',
            'organizationName.unique' => 'Ce nom d\'organisation est déjà utilisé.',
        ]);

        $organization = $action->execute(auth()->user(), $this->organizationName);

        $this->notifyToast('success', 'Votre espace de travail a été créé avec succès !', 'Bienvenue chez ' . $organization->name);

        return redirect()->route('dashboard');
    }

    /**
     * Action : Rejoindre (Placeholder pour Phase 2.2).
     */
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

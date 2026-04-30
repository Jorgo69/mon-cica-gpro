<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\CreateOrganizationAction;
use App\Actions\Invitation\AcceptInvitationAction;
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
     * Action : Créer une organisation (atomique via DB::transaction).
     */
    public function createOrganization(CreateOrganizationAction $action)
    {
        Log::info('[Onboarding] createOrganization - Début', [
            'user_id' => auth()->id(),
            'org_name' => $this->organizationName,
        ]);

        $this->validate([
            'organizationName' => 'required|string|min:3|max:100|unique:organizations,name',
        ], [
            'organizationName.required' => 'Le nom de votre organisation est obligatoire.',
            'organizationName.unique' => 'Ce nom d\'organisation est déjà utilisé.',
        ]);

        Log::info('[Onboarding] createOrganization - Validation OK');

        try {
            $organization = $action->execute(auth()->user(), $this->organizationName);

            Log::info('[Onboarding] createOrganization - Organisation créée', [
                'org_id' => $organization->id,
                'org_name' => $organization->name,
            ]);

            $this->notifyToastSession('success', 'Votre espace de travail a été créé avec succès !', 'Bienvenue chez ' . $organization->name);

            Log::info('[Onboarding] createOrganization - Redirection vers dashboard');

            return redirect()->route('dashboard');
        } catch (\Throwable $e) {
            Log::error('[Onboarding] createOrganization - ÉCHEC', [
                'user_id' => auth()->id(),
                'org_name' => $this->organizationName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->notifyToast('error', 'Une erreur est survenue lors de la création. Veuillez réessayer.', 'Erreur');
        }
    }

    /**
     * Action : Devenir Indépendant (atomique via DB::transaction).
     */
    public function selectIndependent()
    {
        Log::info('[Onboarding] selectIndependent - Début', [
            'user_id' => auth()->id(),
        ]);

        try {
            DB::transaction(function () {
                auth()->user()->update([
                    'role' => \App\Enums\AccountType::INDEPENDENT,
                    'is_independent' => true,
                ]);
            });

            Log::info('[Onboarding] selectIndependent - User mis à jour en indépendant');

            $this->notifyToastSession('success', 'Configuration terminée', 'Bienvenue dans votre espace indépendant !');

            Log::info('[Onboarding] selectIndependent - Redirection vers dashboard');

            return redirect()->route('dashboard');
        } catch (\Throwable $e) {
            Log::error('[Onboarding] selectIndependent - ÉCHEC', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->notifyToast('error', 'Une erreur est survenue. Veuillez réessayer.', 'Erreur');
        }
    }

    /**
     * Action : Rejoindre une organisation via code d'invitation.
     */
    public function joinOrganization()
    {
        $this->validate([
            'inviteCode' => 'required|string|min:6|max:8',
        ], [
            'inviteCode.required' => 'Veuillez saisir votre code d\'invitation.',
        ]);

        $invitation = AcceptInvitationAction::findByCode($this->inviteCode);

        if (!$invitation) {
            $this->notifyToast('error', 'Code invalide ou expiré. Vérifiez auprès de votre administrateur.', 'Code invalide');
            return;
        }

        try {
            (new AcceptInvitationAction)->execute($invitation, auth()->user());

            $orgName = $invitation->organization?->name ?? 'l\'organisation';
            $this->notifyToastSession('success', "Vous avez rejoint {$orgName} avec succès !", 'Bienvenue');

            return redirect()->route('dashboard');
        } catch (\Throwable $e) {
            Log::error('[Onboarding] joinOrganization - ÉCHEC', [
                'user_id' => auth()->id(),
                'code' => $this->inviteCode,
                'error' => $e->getMessage(),
            ]);

            $this->notifyToast('error', $e->getMessage(), 'Erreur');
        }
    }

    public function render()
    {
        return view('livewire.auth.onboarding-livewire')
            ->layout('layouts.guest');
    }
}

<?php

namespace App\Livewire\VBeta\Admin\Invitation;

use App\Actions\Invitation\RevokeInvitationAction;
use App\Actions\Invitation\SendInvitationAction;
use App\Enums\AccountType;
use App\Enums\InvitationStatus;
use App\Livewire\Traits\WithToastNotifications;
use App\Models\Invitation;
use App\Models\Organization;
use Livewire\Component;
use Livewire\WithPagination;

class InvitationManagementLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public $search = '';
    public $statusFilter = '';
    public $showModal = false;

    // Champs du formulaire d'invitation
    public $email = '';
    public $role = 'org_user';
    public $spatieRole = 'MEMBER';
    public $organizationId = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    protected function rules()
    {
        $rules = [
            'email' => 'required|email|max:255',
            'role' => 'required|string',
            'spatieRole' => 'required|string',
        ];

        // ROOT doit spécifier l'org (sauf si en impersonation, elle est auto-remplie)
        $user = auth()->user();
        if ($user->role === AccountType::ROOT) {
            $rules['organizationId'] = 'required|exists:organizations,id';
        }

        return $rules;
    }

    public function openModal()
    {
        $this->reset(['email', 'role', 'spatieRole', 'organizationId']);
        $this->role = 'org_user';
        $this->spatieRole = 'MEMBER';

        // ROOT en impersonation : auto-sélectionner l'org
        if (auth()->user()->role === AccountType::ROOT && session('acting_as_organization_id')) {
            $this->organizationId = session('acting_as_organization_id');
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    public function sendInvitation(SendInvitationAction $action)
    {
        $this->validate();

        try {
            $user = auth()->user();
            $data = [
                'email' => $this->email,
                'role' => $this->role,
                'spatie_role' => $this->spatieRole,
            ];

            // Déterminer l'org cible
            if ($user->role === AccountType::ROOT) {
                $data['organization_id'] = $this->organizationId;
            } else {
                $data['organization_id'] = $user->organization_id;
            }

            $action->execute($data);

            $this->showModal = false;
            $this->reset(['email', 'role', 'spatieRole', 'organizationId']);
            $this->notifyToast('success', 'Invitation envoyée avec succès.', 'Envoyé');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->notifyToast('error', $e->getMessage(), 'Erreur');
        }
    }

    public function resend(string $invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);

        try {
            (new SendInvitationAction)->execute([
                'email' => $invitation->email,
                'role' => $invitation->role,
                'spatie_role' => $invitation->spatie_role,
                'organization_id' => $invitation->organization_id,
            ]);

            // Revoquer l'ancienne
            (new RevokeInvitationAction)->execute($invitation);

            $this->notifyToast('success', 'Nouvelle invitation envoyée.', 'Renvoyé');
        } catch (\Throwable $e) {
            $this->notifyToast('error', $e->getMessage(), 'Erreur');
        }
    }

    public function revoke(string $invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);
        (new RevokeInvitationAction)->execute($invitation);
        $this->notifyToast('success', 'Invitation révoquée.', 'Révoqué');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render()
    {
        $query = Invitation::with(['invitedBy', 'organization'])
            ->when($this->search, fn ($q) => $q->where('email', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderBy('created_at', 'desc');

        return view('livewire.v-beta.admin.invitation.invitation-management-livewire', [
            'invitations' => $query->paginate(10),
            'organizations' => auth()->user()->role === AccountType::ROOT
                ? Organization::orderBy('name')->get()
                : collect(),
            'accountTypes' => collect(AccountType::cases())
                ->filter(fn ($t) => $t !== AccountType::ROOT)
                ->values(),
            'spatieRoles' => ['ORG_ADMIN', 'MANAGER', 'MEMBER', 'SUPERVISOR'],
            'statuses' => InvitationStatus::cases(),
        ]);
    }
}

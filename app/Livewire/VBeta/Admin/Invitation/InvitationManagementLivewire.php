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

    // Modale de confirmation
    public $showConfirmModal = false;
    public $confirmType = ''; // 'resend' or 'revoke'
    public $confirmInvitationId = '';
    public $confirmEmail = '';

    // Champs du formulaire d'invitation
    public $email = '';
    public $selectedRole = 'member'; // single select: member, manager, admin
    public $organizationId = '';

    /**
     * Mapping: selectedRole -> [AccountType, Spatie Role]
     */
    public static function roleMapping(): array
    {
        return [
            'member'  => ['account_type' => 'org_user',  'spatie_role' => 'MEMBER'],
            'manager' => ['account_type' => 'org_user',  'spatie_role' => 'MANAGER'],
            'admin'   => ['account_type' => 'org_admin', 'spatie_role' => 'ORG_ADMIN'],
        ];
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    protected function rules()
    {
        $rules = [
            'email' => 'required|email|max:255',
            'selectedRole' => 'required|in:member,manager,admin',
        ];

        $user = auth()->user();
        if ($user->role === AccountType::ROOT) {
            $rules['organizationId'] = 'required|exists:organizations,id';
        }

        return $rules;
    }

    public function openModal()
    {
        $this->reset(['email', 'selectedRole', 'organizationId']);
        $this->selectedRole = 'member';

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
            $mapping = self::roleMapping()[$this->selectedRole];

            $data = [
                'email' => $this->email,
                'role' => $mapping['account_type'],
                'spatie_role' => $mapping['spatie_role'],
            ];

            if ($user->role === AccountType::ROOT) {
                $data['organization_id'] = $this->organizationId;
            } else {
                $data['organization_id'] = $user->organization_id;
            }

            $action->execute($data);

            $this->showModal = false;
            $this->reset(['email', 'selectedRole', 'organizationId']);
            $this->notifyToast('success', __('admin.invitations.invitation_sent'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->notifyToast('error', $e->getMessage());
        }
    }

    public function openConfirm(string $type, string $invitationId)
    {
        $invitation = Invitation::find($invitationId);
        if (!$invitation) return;

        $this->confirmType = $type;
        $this->confirmInvitationId = $invitationId;
        $this->confirmEmail = $invitation->email;
        $this->showConfirmModal = true;
    }

    public function executeConfirmedAction()
    {
        if ($this->confirmType === 'resend') {
            $this->resend($this->confirmInvitationId);
        } elseif ($this->confirmType === 'revoke') {
            $this->revoke($this->confirmInvitationId);
        }
        $this->showConfirmModal = false;
        $this->reset(['confirmType', 'confirmInvitationId', 'confirmEmail']);
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
        $this->reset(['confirmType', 'confirmInvitationId', 'confirmEmail']);
    }

    public const RESEND_COOLDOWN_MINUTES = 5;

    public function getResendCooldown(string $invitationId): int
    {
        $expiry = cache("invitation-resend-cooldown-{$invitationId}");
        if (!$expiry) return 0;
        return max(0, now()->diffInSeconds($expiry, false));
    }

    public function resend(string $invitationId)
    {
        // Cooldown check
        $remaining = $this->getResendCooldown($invitationId);
        if ($remaining > 0) {
            $minutes = ceil($remaining / 60);
            $this->notifyToast('warning', __('admin.invitations.cooldown', ['minutes' => $minutes]));
            return;
        }

        $invitation = Invitation::findOrFail($invitationId);

        try {
            $data = [
                'email' => $invitation->email,
                'role' => $invitation->role,
                'spatie_role' => $invitation->spatie_role,
                'organization_id' => $invitation->organization_id,
            ];
            (new RevokeInvitationAction)->execute($invitation);
            (new SendInvitationAction)->execute($data);

            // Set cooldown
            cache()->put(
                "invitation-resend-cooldown-{$invitationId}",
                now()->addMinutes(self::RESEND_COOLDOWN_MINUTES),
                self::RESEND_COOLDOWN_MINUTES * 60
            );

            $this->notifyToast('success', __('admin.invitations.invitation_resent'));
        } catch (\Throwable $e) {
            $this->notifyToast('error', $e->getMessage());
        }
    }

    public function revoke(string $invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);
        (new RevokeInvitationAction)->execute($invitation);
        $this->notifyToast('success', __('admin.invitations.invitation_revoked'));
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

        // Roles disponibles selon le role de l'utilisateur courant
        $availableRoles = ['member', 'manager'];
        if (in_array(auth()->user()->role, [AccountType::ROOT, AccountType::ORG_ADMIN])) {
            $availableRoles[] = 'admin';
        }

        return view('livewire.v-beta.admin.invitation.invitation-management-livewire', [
            'invitations' => $query->paginate(10),
            'organizations' => auth()->user()->role === AccountType::ROOT
                ? Organization::orderBy('name')->get()
                : collect(),
            'availableRoles' => $availableRoles,
            'statuses' => InvitationStatus::cases(),
        ]);
    }
}

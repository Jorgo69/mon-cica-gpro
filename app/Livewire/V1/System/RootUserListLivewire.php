<?php

namespace App\Livewire\V1\System;

use App\Enums\AccountType;
use App\Livewire\Traits\WithToastNotifications;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\WithPagination;

class RootUserListLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public $search = '';
    public $organizationFilter = '';
    public $roleFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'organizationFilter' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingOrganizationFilter()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    /**
     * Bloquer/Debloquer un utilisateur.
     * TEMPORAIRE : utilise email_verified_at comme flag de blocage
     * car la colonne is_blocked n'existe pas encore dans la table users.
     * A remplacer par is_blocked quand la migration sera creee.
     */
    public function toggleBlock($id)
    {
        $user = User::withoutGlobalScopes()->findOrFail($id);

        if ($user->id === auth()->id()) {
            $this->notifyToast('warning', 'Vous ne pouvez pas vous bloquer vous-meme.', 'Action impossible');
            return;
        }

        $isBlocked = is_null($user->email_verified_at);

        if ($isBlocked) {
            // Debloquer : restaurer email_verified_at
            $user->email_verified_at = now();
        } else {
            // Bloquer : mettre email_verified_at a null
            $user->email_verified_at = null;
        }

        $user->save();

        $status = $isBlocked ? 'debloque' : 'bloque';
        $this->notifyToast('success', "L'utilisateur {$user->name} a ete {$status}.", 'Statut modifie');
    }

    public function sendPasswordReset($id)
    {
        $user = User::withoutGlobalScopes()->findOrFail($id);

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->notifyToast('success', "Un lien de reinitialisation a ete envoye a {$user->email}.", 'Email envoye');
        } else {
            $this->notifyToast('error', "Impossible d'envoyer le lien de reinitialisation.", 'Erreur');
        }
    }

    public function render()
    {
        $users = User::withoutGlobalScopes()
            ->with('organization:id,name')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            }))
            ->when($this->organizationFilter, fn($q) => $q->where('organization_id', $this->organizationFilter))
            ->when($this->roleFilter, fn($q) => $q->where('role', $this->roleFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.v1.system.root-user-list-livewire', [
            'users' => $users,
            'organizations' => Organization::all(['id', 'name']),
            'accountTypes' => AccountType::cases(),
        ]);
    }
}

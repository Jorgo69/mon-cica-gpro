<?php

namespace App\Livewire\VBeta\Admin\Member;

use App\Actions\Admin\Member\DeleteMemberAction;
use App\Actions\Admin\Member\SaveMemberAction;
use App\Services\Admin\MemberQueryService;
use App\Models\User;
use App\Enums\AccountType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Livewire\Traits\WithToastNotifications;

class MemberManagementLivewire extends Component
{
    use WithPagination, WithFileUploads, AuthorizesRequests, WithToastNotifications;

    // Filtres et Tri
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Gestion des modals
    public $showModal = false;
    public $modalType = 'create'; // create, edit, view, delete
    public ?User $selectedMember = null;

    // Champs formulaire (liés directement au x-ui.input/select)
    public $name, $email, $password, $telephone, $sexe, $numero_identification, $pays, $ville, $role, $department, $image;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    /**
     * Règles de validation centralisées.
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($this->selectedMember ? ',' . $this->selectedMember->id : ''),
            'password' => $this->modalType === 'create' ? 'required|min:6' : 'nullable|min:6',
            'telephone' => 'nullable|string|max:50',
            'sexe' => 'nullable|string|max:20',
            'numero_identification' => 'nullable|string|max:100|unique:users,numero_identification' . ($this->selectedMember ? ',' . $this->selectedMember->id : ''),
            'pays' => 'nullable|string|max:100',
            'ville' => 'nullable|string|max:100',
            'role' => ['required', new Enum(AccountType::class), function ($attribute, $value, $fail) {
                $assignable = auth()->user()->role->assignableRoles();
                $target = AccountType::tryFrom($value);
                if (!$target || !in_array($target, $assignable)) {
                    $fail("Vous ne pouvez pas assigner ce rôle.");
                }
            }],
            'department' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    /**
     * Ouvre le modal spécifié.
     */
    public function openModal($type, $id = null, MemberQueryService $queryService)
    {
        $this->authorize('viewAny', User::class);
        
        $this->resetValidation();
        $this->resetForm();
        $this->modalType = $type;
        $this->showModal = true;

        if ($id) {
            $this->selectedMember = $queryService->findById($id);
            if ($this->selectedMember) {
                $this->authorize('view', $this->selectedMember);
                $this->fill($this->selectedMember->toArray());
                // On s'assure que le role est bien l'enum value pour le select
                $this->role = $this->selectedMember->role->value;
            }
        }
    }

    /**
     * Ferme le modal et réinitialise l'état.
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Action de sauvegarde (Création).
     */
    public function store(SaveMemberAction $saveAction)
    {
        $this->authorize('create', User::class);
        $data = $this->validate();
        
        $saveAction->execute($data);

        $this->closeModal();
        $this->notifyToast('success', 'Le membre a été créé avec succès.', 'Nouveau Membre');
    }

    /**
     * Action de mise à jour.
     */
    public function update(SaveMemberAction $saveAction)
    {
        $this->authorize('update', $this->selectedMember);
        $data = $this->validate();
        
        $saveAction->execute($data, $this->selectedMember);

        $this->closeModal();
        $this->notifyToast('success', 'Les informations du membre ont été mises à jour.', 'Mise à jour');
    }

    /**
     * Action de suppression.
     */
    public function delete(DeleteMemberAction $deleteAction)
    {
        $this->authorize('delete', $this->selectedMember);
        
        $deleteAction->execute($this->selectedMember);
        
        $this->closeModal();
        $this->notifyToast('success', 'Le membre a été supprimé de l\'organisation.', 'Suppression effectuée');
    }

    /**
     * Réinitialise les champs du formulaire.
     */
    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'telephone', 'sexe', 'numero_identification', 'pays', 'ville', 'role', 'department', 'image', 'selectedMember']);
    }

    /**
     * Rendu de la vue avec injection du service de requête.
     */
    public function placeholder()
    {
        return view('components.ui.skeleton-table');
    }

    public function render(MemberQueryService $queryService)
    {
        return view('livewire.v-beta.admin.member.member-management-livewire', [
            'members' => $queryService->list(
                search: $this->search,
                sortField: $this->sortField,
                sortDirection: $this->sortDirection,
            ),
            'assignableRoles' => auth()->user()->role->assignableRoles(),
        ]);
    }
}

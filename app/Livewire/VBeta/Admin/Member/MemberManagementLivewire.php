<?php

namespace App\Livewire\VBeta\Admin\Member;

use App\Actions\Admin\Member\DeleteMemberAction;
use App\Actions\Admin\Member\SaveMemberAction;
use App\Services\Admin\MemberQueryService;
use App\Models\User;
use App\Enums\OrgMemberRole;
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
    public $modalType = 'create';
    public ?User $selectedMember = null;

    // Champs formulaire
    public $name, $email, $password, $telephone, $numero_identification;
    public $country, $ville, $quartier; // location fields
    public $org_role; // rôle dans l'org (OrgMemberRole)
    public $spatie_role; // rôle Spatie (MANAGER, MEMBER, SUPERVISOR)
    public $image;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected function rules()
    {
        return [
            'name'                   => 'required|string|max:255',
            'email'                  => 'required|email|unique:users,email' . ($this->selectedMember ? ',' . $this->selectedMember->id : ''),
            'password'               => $this->modalType === 'create' ? 'required|min:6' : 'nullable|min:6',
            'telephone'              => 'nullable|string|max:50',
            'numero_identification'  => 'nullable|string|max:100|unique:users,numero_identification' . ($this->selectedMember ? ',' . $this->selectedMember->id : ''),
            'country'                => 'nullable|string|max:10',
            'ville'                  => 'nullable|string|max:100',
            'quartier'               => 'nullable|string|max:100',
            'org_role'               => ['required', new Enum(OrgMemberRole::class)],
            'spatie_role'            => 'required|string|in:ORG_ADMIN,MANAGER,MEMBER,SUPERVISOR',
            'image'                  => 'nullable|image|max:2048',
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

                $this->name                   = $this->selectedMember->name;
                $this->email                  = $this->selectedMember->email;
                $this->telephone              = $this->selectedMember->telephone;
                $this->numero_identification  = $this->selectedMember->numero_identification;
                $this->country                = $this->selectedMember->country;
                $this->ville                  = $this->selectedMember->location['ville'] ?? null;
                $this->quartier               = $this->selectedMember->location['quartier'] ?? null;

                // Rôle pivot dans l'org active
                $orgId = session('current_organization_id');
                $pivot = $this->selectedMember->organizations()->where('organizations.id', $orgId)->first()?->pivot;
                $this->org_role = $pivot?->role ?? OrgMemberRole::MEMBER->value;

                // Rôle Spatie
                $this->spatie_role = $this->selectedMember->getRoleNames()->first() ?? 'MEMBER';
            }
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function store(SaveMemberAction $saveAction)
    {
        $this->authorize('create', User::class);
        $data = $this->validate();
        $data['location'] = array_filter(['ville' => $this->ville, 'quartier' => $this->quartier]);

        $saveAction->execute($data);

        $this->closeModal();
        $this->notifyToast('success', 'Le membre a été créé avec succès.', 'Nouveau Membre');
    }

    public function update(SaveMemberAction $saveAction)
    {
        $this->authorize('update', $this->selectedMember);
        $data = $this->validate();
        $data['location'] = array_filter(['ville' => $this->ville, 'quartier' => $this->quartier]);

        $saveAction->execute($data, $this->selectedMember);

        $this->closeModal();
        $this->notifyToast('success', 'Les informations du membre ont été mises à jour.', 'Mise à jour');
    }

    public function delete(DeleteMemberAction $deleteAction)
    {
        $this->authorize('delete', $this->selectedMember);

        $deleteAction->execute($this->selectedMember);

        $this->closeModal();
        $this->notifyToast('success', 'Le membre a été supprimé de l\'organisation.', 'Suppression effectuée');
    }

    private function resetForm()
    {
        $this->reset([
            'name', 'email', 'password', 'telephone', 'numero_identification',
            'country', 'ville', 'quartier', 'org_role', 'spatie_role', 'image', 'selectedMember',
        ]);
    }

    public function render(MemberQueryService $queryService)
    {
        return view('livewire.admin.member.management', [
            'members' => $queryService->list(
                search: $this->search,
                sortField: $this->sortField,
                sortDirection: $this->sortDirection,
            ),
        ]);
    }
}

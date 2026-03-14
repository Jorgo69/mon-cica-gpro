<?php

namespace App\Livewire\VBeta\Admin\Member;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\AccountType;
use Illuminate\Validation\Rules\Enum;

class MemberManagementLivewire extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Gestion des modals
    public $showModal = false;
    public $modalType = 'create'; // create, edit, view, delete
    public $selectedMember = null;

    // Champs formulaire
    public $name, $email, $password, $telephone, $sexe, $numero_identification, $pays, $ville, $role, $department, $image;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

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
            'role' => ['required', new Enum(AccountType::class)],
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

    public function openModal($type, $id = null)
    {
        $this->resetValidation();
        $this->resetForm();
        $this->modalType = $type;
        $this->showModal = true;

        if ($id) {
            $this->selectedMember = User::findOrFail($id);
            $this->fill($this->selectedMember->toArray());
        }
    }

    public function store()
    {
        $this->validate();

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->telephone = $this->telephone;
        $user->sexe = $this->sexe;
        $user->numero_identification = $this->numero_identification;
        $user->pays = $this->pays;
        $user->ville = $this->ville;
        $user->role = $this->role;
        $user->department = $this->department;

        if ($this->image) {
            $user->image = $this->image->store('members', 'public');
        }

        $user->save();

        $this->showModal = false;
        $this->resetForm();

        session()->flash('success', 'Utilisateur ajouter avec success');
    }

    public function update()
    {
        $this->validate();

        $user = $this->selectedMember;
        $user->name = $this->name;
        $user->email = $this->email;
        if ($this->password) {
            $user->password = Hash::make($this->password);
        }
        $user->telephone = $this->telephone;
        $user->sexe = $this->sexe;
        $user->numero_identification = $this->numero_identification;
        $user->pays = $this->pays;
        $user->ville = $this->ville;
        $user->role = $this->role;
        $user->department = $this->department;

        if ($this->image) {
            $user->image = $this->image->store('members', 'public');
        }

        $user->save();

        $this->showModal = false;
        $this->resetForm();
        // dd();
        session()->flash('info-project', 'Info mis a jour');
    }

    public function delete()
    {
        if ($this->selectedMember) {
            $this->selectedMember->delete();
        }
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = $this->email = $this->password = $this->telephone = $this->sexe =
        $this->numero_identification = $this->pays = $this->ville = $this->role = $this->department = $this->image = null;
        $this->selectedMember = null;
    }

    public function render()
    {
        $members = User::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.v-beta.admin.member.member-management-livewire', [
            'members' => $members,
        ]);
    }
}

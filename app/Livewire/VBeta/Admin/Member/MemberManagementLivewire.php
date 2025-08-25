<?php

namespace App\Livewire\VBeta\Admin\Member;

use Livewire\WithPagination;
use App\Models\User;
use Livewire\Component;

class MemberManagementLivewire extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $responsibleUserFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $projectStatuses = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'responsibleUserFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingResponsibleUserFilter()
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


    public function render()
    {
        $members = User::query();
        
        return view('livewire.v-beta.admin.member.member-management-livewire', [
            'members' => $members->paginate(10),
        ]);
    }
}

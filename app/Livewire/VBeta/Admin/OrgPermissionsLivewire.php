<?php

namespace App\Livewire\VBeta\Admin;

use App\Enums\PermissionLevel;
use App\Livewire\Traits\WithToastNotifications;
use App\Models\User;
use Livewire\Component;

class OrgPermissionsLivewire extends Component
{
    use WithToastNotifications;

    public string $search = '';

    public function render()
    {
        $orgId = auth()->user()->organization_id;

        $members = User::where('organization_id', $orgId)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                $permissions = $user->getAllPermissions()->pluck('name')->toArray();
                $level = $this->detectLevel($permissions);
                return (object) [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'level' => $level,
                    'permissions' => $permissions,
                ];
            });

        return view('livewire.v-beta.admin.org-permissions-livewire', [
            'members' => $members,
            'allPermissions' => collect(PermissionLevel::ADMIN->permissions())->unique()->sort()->values(),
        ]);
    }

    protected function detectLevel(array $permissions): PermissionLevel
    {
        $detected = PermissionLevel::OBSERVER;
        foreach (PermissionLevel::cases() as $level) {
            if (empty(array_diff($level->permissions(), $permissions))) {
                $detected = $level;
            }
        }
        return $detected;
    }
}

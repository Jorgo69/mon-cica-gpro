<?php

namespace App\Livewire\V1\System;

use App\Enums\AccountType;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Livewire\Component;

class RootDashboardLivewire extends Component
{
    public function render()
    {
        // Stats globales (ROOT bypass le Global Scope)
        $totalOrganizations = Organization::count();
        $totalUsers = User::withoutGlobalScopes()->count();
        $totalProjects = Project::withoutGlobalScopes()->count();
        $pendingInvitations = Invitation::withoutGlobalScopes()
            ->where('status', \App\Enums\InvitationStatus::PENDING)
            ->count();

        // Top 10 organisations par nombre de projets
        $topOrganizations = Organization::query()
            ->withCount(['users', 'projects'])
            ->orderByDesc('projects_count')
            ->limit(10)
            ->get();

        // Derniers utilisateurs inscrits
        $recentUsers = User::withoutGlobalScopes()
            ->with('organization:id,name')
            ->latest()
            ->limit(10)
            ->get(['id', 'name', 'email', 'role', 'organization_id', 'created_at']);

        // Invitations en attente
        $recentInvitations = Invitation::withoutGlobalScopes()
            ->with(['organization:id,name', 'invitedBy:id,name'])
            ->where('status', \App\Enums\InvitationStatus::PENDING)
            ->latest()
            ->limit(10)
            ->get();

        return view('livewire.v1.system.root-dashboard-livewire', compact(
            'totalOrganizations',
            'totalUsers',
            'totalProjects',
            'pendingInvitations',
            'topOrganizations',
            'recentUsers',
            'recentInvitations',
        ));
    }
}

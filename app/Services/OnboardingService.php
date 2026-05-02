<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Models\User;

class OnboardingService
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function for(User $user): self
    {
        return new self($user);
    }

    public function isCompleted(): bool
    {
        return (bool) $this->user->getMeta('onboarding_completed', false);
    }

    public function dismiss(): void
    {
        $this->user->setMeta('onboarding_completed', true);
    }

    public function getSteps(): array
    {
        $role = $this->user->role;

        if ($role === AccountType::ROOT) {
            return [];
        }

        if ($role === AccountType::INDEPENDENT) {
            return $this->independentSteps();
        }

        if ($role === AccountType::ORG_ADMIN) {
            return $this->orgAdminSteps();
        }

        return $this->memberSteps();
    }

    public function progress(): array
    {
        $steps = $this->getSteps();
        $total = count($steps);
        $done = collect($steps)->where('done', true)->count();

        return [
            'steps' => $steps,
            'total' => $total,
            'done' => $done,
            'percent' => $total > 0 ? round(($done / $total) * 100) : 100,
        ];
    }

    protected function orgAdminSteps(): array
    {
        $user = $this->user;
        $orgId = $user->organization_id;

        $hasProject = $orgId
            ? \App\Models\Project::where('organization_id', $orgId)->exists()
            : false;

        $hasMembers = $orgId
            ? \App\Models\User::where('organization_id', $orgId)->where('id', '!=', $user->id)->exists()
            : false;

        $hasInvitation = $orgId
            ? \App\Models\Invitation::where('organization_id', $orgId)->exists()
            : false;

        $hasAvatar = (bool) $user->getMeta('avatar');

        $hasProjectType = $orgId
            ? \App\Models\ProjectType::where('organization_id', $orgId)->exists()
            : false;

        return [
            [
                'key' => 'complete_profile',
                'label' => __('onboarding.complete_profile'),
                'desc' => __('onboarding.complete_profile_desc'),
                'icon' => 'user-check',
                'done' => $hasAvatar && $user->telephone,
                'route' => 'profile.edit',
            ],
            [
                'key' => 'invite_team',
                'label' => __('onboarding.invite_team'),
                'desc' => __('onboarding.invite_team_desc'),
                'icon' => 'user-plus',
                'done' => $hasMembers || $hasInvitation,
                'route' => 'admin.invitation.list',
            ],
            [
                'key' => 'create_project',
                'label' => __('onboarding.create_project'),
                'desc' => __('onboarding.create_project_desc'),
                'icon' => 'folder-plus',
                'done' => $hasProject,
                'route' => 'creator.proposal.project.create',
            ],
            [
                'key' => 'explore_settings',
                'label' => __('onboarding.explore_settings'),
                'desc' => __('onboarding.explore_settings_desc'),
                'icon' => 'settings',
                'done' => (bool) $user->getMeta('visited_settings'),
                'route' => 'setting',
            ],
        ];
    }

    protected function memberSteps(): array
    {
        $user = $this->user;
        $hasAvatar = (bool) $user->getMeta('avatar');

        $hasProject = \App\Models\Project::where('creator_user_id', $user->id)->exists();

        return [
            [
                'key' => 'complete_profile',
                'label' => __('onboarding.complete_profile'),
                'desc' => __('onboarding.complete_profile_desc'),
                'icon' => 'user-check',
                'done' => $hasAvatar && $user->telephone,
                'route' => 'profile.edit',
            ],
            [
                'key' => 'create_project',
                'label' => __('onboarding.create_first_project'),
                'desc' => __('onboarding.create_first_project_desc'),
                'icon' => 'folder-plus',
                'done' => $hasProject,
                'route' => 'creator.proposal.project.create',
            ],
            [
                'key' => 'explore_dashboard',
                'label' => __('onboarding.explore_dashboard'),
                'desc' => __('onboarding.explore_dashboard_desc'),
                'icon' => 'layout-dashboard',
                'done' => (bool) $user->getMeta('visited_dashboard'),
                'route' => 'dashboard',
            ],
        ];
    }

    protected function independentSteps(): array
    {
        $user = $this->user;
        $hasAvatar = (bool) $user->getMeta('avatar');
        $hasProject = \App\Models\Project::where('creator_user_id', $user->id)->exists();

        return [
            [
                'key' => 'complete_profile',
                'label' => __('onboarding.complete_profile'),
                'desc' => __('onboarding.complete_profile_desc'),
                'icon' => 'user-check',
                'done' => $hasAvatar && $user->telephone,
                'route' => 'profile.edit',
            ],
            [
                'key' => 'create_project',
                'label' => __('onboarding.create_first_project'),
                'desc' => __('onboarding.create_first_project_desc'),
                'icon' => 'folder-plus',
                'done' => $hasProject,
                'route' => 'creator.proposal.project.create',
            ],
        ];
    }
}

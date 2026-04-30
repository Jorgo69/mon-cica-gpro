<?php

namespace App\Services\Search;

use App\Enums\AccountType;
use App\Models\Project;
use App\Models\Activity;
use App\Models\User;
use App\Models\SpecificObjective;
use App\Models\Result;
use App\Services\OrgContext;
use Illuminate\Support\Collection;

class GlobalSearchService
{
    public function search(string $term, int $limit = 5): Collection
    {
        if (empty($term) || strlen($term) < 2) {
            return collect();
        }

        // Escape LIKE wildcards
        $term = str_replace(['%', '_'], ['\\%', '\\_'], $term);

        $results = collect();
        $user = auth()->user();

        // Projets — tout le monde peut chercher (Multitenantable filtre)
        $results = $results->concat($this->searchProjects($term, $limit));

        // Activites — tout le monde
        $results = $results->concat($this->searchActivities($term, $limit));

        // Membres — admin ou users avec permission manage-users
        if (in_array($user->role, [AccountType::ROOT, AccountType::ORG_ADMIN])
            || $user->hasPermissionTo('manage-users')) {
            $results = $results->concat($this->searchMembers($term, $limit));
        }

        // Objectifs specifiques — tout le monde
        $results = $results->concat($this->searchObjectives($term, $limit));

        // Resultats — tout le monde
        $results = $results->concat($this->searchResults($term, $limit));

        return $results;
    }

    public function quickActions(): array
    {
        $user = auth()->user();
        $actions = [];

        // Nouveau projet : org_admin, org_user avec permission, independent — pas ROOT
        if ($user->role !== AccountType::ROOT && $user->can('create', \App\Models\Project::class)) {
            $actions[] = [
                'type' => 'action',
                'title' => 'Nouveau projet',
                'subtitle' => 'Creer une nouvelle proposition de projet',
                'url' => route('creator.proposal.project.create'),
                'icon' => 'plus-circle',
                'category' => 'Actions',
            ];
        }

        // Gerer les membres : admin ou permission manage-users
        if (in_array($user->role, [AccountType::ROOT, AccountType::ORG_ADMIN])
            || $user->hasPermissionTo('manage-users')) {
            $actions[] = [
                'type' => 'action',
                'title' => 'Gerer les membres',
                'subtitle' => 'Voir et gerer les membres de l\'organisation',
                'url' => route('admin.member.list'),
                'icon' => 'users',
                'category' => 'Actions',
            ];
        }

        $actions[] = [
            'type' => 'action',
            'title' => 'Parametres',
            'subtitle' => 'Modifier vos preferences',
            'url' => route('setting'),
            'icon' => 'settings',
            'category' => 'Actions',
        ];

        return $actions;
    }

    private function searchProjects(string $term, int $limit): Collection
    {
        return Project::query()
            ->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('project_code', 'like', "%{$term}%")
                    ->orWhere('short_title', 'like', "%{$term}%");
            })
            ->limit($limit)
            ->get()
            ->map(fn($p) => [
                'type' => 'result',
                'title' => $p->title,
                'subtitle' => $p->project_code . ' — ' . ($p->status?->label() ?? ''),
                'url' => route('project.show', $p->id),
                'icon' => 'folder-kanban',
                'category' => 'Projets',
            ]);
    }

    private function searchActivities(string $term, int $limit): Collection
    {
        return Activity::query()
            ->where('description', 'like', "%{$term}%")
            ->with('responsibleUser')
            ->limit($limit)
            ->get()
            ->map(fn($a) => [
                'type' => 'result',
                'title' => $a->description,
                'subtitle' => ($a->responsibleUser?->name ?? '') . ' — ' . ($a->status?->label() ?? ''),
                'url' => route('activity.list'),
                'icon' => 'list-checks',
                'category' => 'Activites',
            ]);
    }

    private function searchMembers(string $term, int $limit): Collection
    {
        return User::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            })
            ->limit($limit)
            ->get()
            ->map(fn($u) => [
                'type' => 'result',
                'title' => $u->name,
                'subtitle' => $u->email . ' — ' . ($u->role?->label() ?? ''),
                'url' => route('admin.member.list'),
                'icon' => 'user',
                'category' => 'Membres',
            ]);
    }

    private function searchObjectives(string $term, int $limit): Collection
    {
        return SpecificObjective::query()
            ->where('description', 'like', "%{$term}%")
            ->with('logicalFramework')
            ->limit($limit)
            ->get()
            ->map(fn($o) => [
                'type' => 'result',
                'title' => $o->description,
                'subtitle' => 'Cadre logique — ' . ($o->logicalFramework?->project?->short_title ?? ''),
                'url' => $o->logicalFramework?->project_id
                    ? route('project.show', $o->logicalFramework->project_id)
                    : '#',
                'icon' => 'target',
                'category' => 'Objectifs',
            ]);
    }

    private function searchResults(string $term, int $limit): Collection
    {
        return Result::query()
            ->where('description', 'like', "%{$term}%")
            ->with('specificObjective.logicalFramework')
            ->limit($limit)
            ->get()
            ->map(fn($r) => [
                'type' => 'result',
                'title' => $r->description,
                'subtitle' => 'Resultat attendu',
                'url' => $r->specificObjective?->logicalFramework?->project_id
                    ? route('project.show', $r->specificObjective->logicalFramework->project_id)
                    : '#',
                'icon' => 'check-square',
                'category' => 'Resultats',
            ]);
    }
}

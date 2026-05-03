<?php

namespace App\Livewire\VBeta\Project;

use App\Livewire\Traits\WithToastNotifications;
use App\Models\Project;
use App\Services\ProjectTemplateService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectTemplateListLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function duplicateFromTemplate(string $templateId)
    {
        $template = Project::where('is_template', true)->findOrFail($templateId);

        $service = app(ProjectTemplateService::class);
        $newProject = $service->duplicate($template);

        $this->notifyToastSession('success', __('projects.templates.duplicated'));

        return $this->redirect(
            route('creator.proposal.project.edit', ['projectId' => $newProject->id]),
            navigate: true
        );
    }

    public function toggleTemplate(string $projectId)
    {
        $project = Project::where('creator_user_id', Auth::id())->findOrFail($projectId);

        $service = app(ProjectTemplateService::class);
        $service->toggleTemplate($project);

        $message = $project->fresh()->is_template
            ? __('projects.templates.marked')
            : __('projects.templates.unmarked');

        $this->notifyToast('success', $message);
    }

    public function render()
    {
        $user = Auth::user();

        $templates = Project::query()
            ->where('is_template', true)
            ->where(function ($query) use ($user) {
                $query->whereNull('organization_id')
                      ->orWhere('organization_id', $user->organization_id);
            })
            ->when($this->search, function ($query) {
                $term = str_replace(['%', '_'], ['\\%', '\\_'], $this->search);
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', "%{$term}%")
                      ->orWhere('short_title', 'like', "%{$term}%");
                });
            })
            ->with(['logicalFramework.specificObjectives.results.activities', 'projectType', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $templates->getCollection()->transform(function ($project) {
            $objectivesCount = 0;
            $activitiesCount = 0;

            if ($project->logicalFramework) {
                $objectivesCount = $project->logicalFramework->specificObjectives->count();
                foreach ($project->logicalFramework->specificObjectives as $objective) {
                    foreach ($objective->results as $result) {
                        $activitiesCount += $result->activities->count();
                    }
                }
            }

            $project->computed_objectives_count = $objectivesCount;
            $project->computed_activities_count = $activitiesCount;

            return $project;
        });

        return view('livewire.v-beta.project.project-template-list-livewire', [
            'templates' => $templates,
        ]);
    }
}

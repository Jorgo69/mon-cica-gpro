<?php

namespace App\Services;

use App\Enums\ActivityStatus;
use App\Enums\ProjectStatus;
use App\Models\Activity;
use App\Models\Budget;
use App\Models\Indicator;
use App\Models\LogicalFramework;
use App\Models\Project;
use App\Models\Resource;
use App\Models\Result;
use App\Models\SpecificObjective;
use App\Services\Queries\LogframeQueryService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectTemplateService
{
    /**
     * Duplicate a project with its entire logframe hierarchy.
     * Creates a new project in DRAFT status with a new code.
     * Copies: LogicalFramework -> SpecificObjectives -> Results -> Activities (+ children) -> Resources
     * Also copies: Budgets (project-level), Indicators (polymorphic on LF, SO, Result)
     * Does NOT copy: Documents, Comments, Attachments, Expenses, ProgressTrackers, QualitativeEvaluations
     */
    public function duplicate(Project $source, array $overrides = []): Project
    {
        $source = LogframeQueryService::forProject($source->id)->project();

        if (! $source) {
            throw new \InvalidArgumentException('Source project not found.');
        }

        return DB::transaction(function () use ($source, $overrides) {
            $newProject = $this->cloneProject($source, $overrides);

            if ($source->logicalFramework) {
                $newLf = $this->cloneLogicalFramework($source->logicalFramework, $newProject);

                $this->cloneIndicators($source->logicalFramework, $newLf);

                foreach ($source->logicalFramework->specificObjectives as $so) {
                    $newSo = $this->cloneSpecificObjective($so, $newLf);

                    $this->cloneIndicators($so, $newSo);

                    foreach ($so->results as $result) {
                        $newResult = $this->cloneResult($result, $newSo);

                        $this->cloneIndicators($result, $newResult);

                        foreach ($result->activities as $activity) {
                            $this->cloneActivityTree($activity, $newResult->id, null);
                        }
                    }
                }
            }

            $this->cloneBudgets($source, $newProject);

            return $newProject;
        });
    }

    /**
     * Mark/unmark a project as a template.
     */
    public function toggleTemplate(Project $project): Project
    {
        $project->update(['is_template' => ! $project->is_template]);

        return $project;
    }

    /**
     * Get all templates visible to the current user (system templates + org templates).
     */
    public function getAvailableTemplates(): Collection
    {
        $user = auth()->user();

        return Project::where('is_template', true)
            ->where(function ($query) use ($user) {
                $query->whereNull('organization_id')
                    ->orWhere('organization_id', $user->organization_id);
            })
            ->orderBy('title')
            ->get();
    }

    // ------------------------------------------------------------------
    // Private clone helpers
    // ------------------------------------------------------------------

    private function cloneProject(Project $source, array $overrides): Project
    {
        $attributes = array_merge([
            'title' => 'Copie de ' . $source->title,
            'short_title' => $source->short_title,
            'project_code' => 'CPY-' . strtoupper(Str::random(8)),
            'project_type_id' => $source->project_type_id,
            'description' => $source->description,
            'problem_analysis' => $source->problem_analysis,
            'strategy' => $source->strategy,
            'justification' => $source->justification,
            'context_description' => $source->context_description,
            'general_objectives' => $source->general_objectives,
            'currency' => $source->currency,
            'start_date' => $source->start_date,
            'end_date' => $source->end_date,
            'status' => ProjectStatus::DRAFT,
            'creator_user_id' => auth()->id(),
            'organization_id' => auth()->user()->organization_id,
            'source_project_id' => $source->id,
            'is_template' => false,
        ], $overrides);

        return Project::create($attributes);
    }

    private function cloneLogicalFramework(LogicalFramework $lf, Project $newProject): LogicalFramework
    {
        return LogicalFramework::create([
            'organization_id' => $newProject->organization_id,
            'project_id' => $newProject->id,
            'creator_user_id' => auth()->id(),
            'general_objective' => $lf->general_objective,
            'general_obj_indicators' => $lf->general_obj_indicators,
            'general_obj_verification_sources' => $lf->general_obj_verification_sources,
            'assumptions' => $lf->assumptions,
            'meta' => $lf->meta,
        ]);
    }

    private function cloneSpecificObjective(SpecificObjective $so, LogicalFramework $newLf): SpecificObjective
    {
        return SpecificObjective::create([
            'organization_id' => $newLf->organization_id,
            'logical_framework_id' => $newLf->id,
            'creator_user_id' => auth()->id(),
            'description' => $so->description,
            'indicators' => $so->indicators,
            'verification_sources' => $so->verification_sources,
            'assumptions' => $so->assumptions,
            'meta' => $so->meta,
        ]);
    }

    private function cloneResult(Result $result, SpecificObjective $newSo): Result
    {
        return Result::create([
            'organization_id' => $newSo->organization_id,
            'specific_objective_id' => $newSo->id,
            'creator_user_id' => auth()->id(),
            'description' => $result->description,
            'meta' => $result->meta,
        ]);
    }

    /**
     * Recursively clone an activity and its children (sub-activities).
     */
    private function cloneActivityTree(Activity $activity, string $resultId, ?string $parentId): Activity
    {
        $newActivity = Activity::create([
            'organization_id' => auth()->user()->organization_id,
            'result_id' => $resultId,
            'parent_id' => $parentId,
            'creator_user_id' => auth()->id(),
            'description' => $activity->description,
            'start_date' => $activity->start_date,
            'end_date' => $activity->end_date,
            'budget' => $activity->budget,
            'status' => ActivityStatus::DRAFT,
            'justification' => $activity->justification,
            'is_milestone' => $activity->is_milestone,
            'progress_percentage' => 0,
            'meta' => $activity->meta,
        ]);

        $this->cloneResources($activity, $newActivity);

        // Recurse into children (sub-activities)
        $activity->loadMissing('children');
        foreach ($activity->children as $child) {
            $this->cloneActivityTree($child, $resultId, $newActivity->id);
        }

        return $newActivity;
    }

    private function cloneResources(Activity $source, Activity $target): void
    {
        foreach ($source->resources as $resource) {
            Resource::create([
                'organization_id' => $target->organization_id,
                'activity_id' => $target->id,
                'creator_user_id' => auth()->id(),
                'name' => $resource->name,
                'type' => $resource->type,
                'quantity' => $resource->quantity,
                'unit_cost' => $resource->unit_cost,
                'total_cost' => $resource->total_cost,
                'category' => $resource->category,
            ]);
        }
    }

    private function cloneBudgets(Project $source, Project $target): void
    {
        foreach ($source->budgets as $budget) {
            Budget::create([
                'organization_id' => $target->organization_id,
                'project_id' => $target->id,
                'creator_user_id' => auth()->id(),
                'description' => $budget->description,
                'quantity' => $budget->quantity,
                'unit_cost' => $budget->unit_cost,
                'total_cost' => $budget->total_cost,
                'category' => $budget->category,
            ]);
        }
    }

    /**
     * Clone polymorphic indicators from a source model to a target model.
     * Works for LogicalFramework, SpecificObjective, and Result.
     */
    private function cloneIndicators(
        LogicalFramework|SpecificObjective|Result $source,
        LogicalFramework|SpecificObjective|Result $target
    ): void {
        $source->loadMissing('indicatorItems');

        foreach ($source->indicatorItems as $indicator) {
            Indicator::create([
                'organization_id' => $target->organization_id ?? auth()->user()->organization_id,
                'indicatorable_type' => get_class($target),
                'indicatorable_id' => $target->id,
                'description' => $indicator->description,
                'verification_source' => $indicator->verification_source,
                'assumption' => $indicator->assumption,
                'baseline_value' => $indicator->baseline_value,
                'target_value' => $indicator->target_value,
                'creator_user_id' => auth()->id(),
                'order' => $indicator->order,
            ]);
        }
    }
}

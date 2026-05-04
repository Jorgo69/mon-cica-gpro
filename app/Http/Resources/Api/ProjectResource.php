<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->project_code,
            'title' => $this->title,
            'short_title' => $this->short_title,
            'description' => strip_tags($this->description ?? ''),
            'status' => $this->status?->value,
            'status_key' => $this->status?->name,
            'progress' => $this->calculateProjectProgress(),
            'currency' => $this->currency?->value,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'creator' => $this->whenLoaded('creator', fn () => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
            ]),
            'type' => $this->whenLoaded('projectType', fn () => [
                'id' => $this->projectType->id,
                'name' => $this->projectType->name,
            ]),
            'logframe' => $this->when($request->routeIs('api.v1.projects.show'), function () {
                $lf = $this->logicalFramework;
                if (!$lf) return null;
                return [
                    'general_objective' => $lf->general_objective,
                    'specific_objectives' => $lf->specificObjectives->map(fn ($so) => [
                        'description' => $so->description,
                        'results' => $so->results->map(fn ($r) => [
                            'description' => $r->description,
                            'activities_count' => $r->activities->count(),
                        ]),
                    ]),
                ];
            }),
            'stats' => [
                'activities_count' => $this->whenCounted('activities_count'),
                'budget_planned' => $this->whenLoaded('budgets', fn () => $this->budgets->sum('total_cost')),
                'budget_spent' => $this->whenLoaded('expenses', fn () => $this->expenses->sum('amount')),
            ],
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

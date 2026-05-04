<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => strip_tags($this->description ?? ''),
            'status' => $this->status?->value,
            'status_key' => $this->status?->name,
            'progress' => $this->progress_percentage,
            'budget' => $this->budget,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'is_milestone' => $this->is_milestone,
            'responsible' => $this->whenLoaded('responsibleUser', fn () => [
                'id' => $this->responsibleUser->id,
                'name' => $this->responsibleUser->name,
                'email' => $this->responsibleUser->email,
            ]),
            'project' => $this->when(
                $this->relationLoaded('result'),
                fn () => [
                    'id' => $this->result?->specificObjective?->logicalFramework?->project?->id,
                    'title' => $this->result?->specificObjective?->logicalFramework?->project?->title,
                ]
            ),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

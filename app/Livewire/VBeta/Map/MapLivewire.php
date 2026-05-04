<?php

namespace App\Livewire\VBeta\Map;

use App\Models\Project;
use Livewire\Component;

class MapLivewire extends Component
{
    public ?string $statusFilter = null;

    public function render()
    {
        $user = auth()->user();
        $geocoding = config('geocoding', []);

        $query = Project::query()
            ->visibleTo($user)
            ->with(['creator:id,name,pays'])
            ->when($this->statusFilter, fn ($q, $s) => $q->where('status', $s));

        $projects = $query->get();

        // Build markers from projects
        $markers = [];
        foreach ($projects as $project) {
            $countryCode = $this->resolveCountryCode($project);
            if (!$countryCode || !isset($geocoding[$countryCode])) continue;

            [$lat, $lng] = $geocoding[$countryCode];

            // Add small random offset to prevent stacking
            $lat += (rand(-50, 50) / 1000);
            $lng += (rand(-50, 50) / 1000);

            $markers[] = [
                'id' => $project->id,
                'title' => $project->title,
                'status' => $project->status?->value,
                'statusKey' => $project->status?->name,
                'color' => $project->status?->hex() ?? '#94a3b8',
                'progress' => $project->calculateProjectProgress(),
                'creator' => $project->creator?->name,
                'country' => $countryCode,
                'lat' => $lat,
                'lng' => $lng,
                'url' => route('project.show', $project->id),
            ];
        }

        return view('livewire.v-beta.map.map-livewire', [
            'markers' => $markers,
            'statuses' => \App\Enums\ProjectStatus::cases(),
        ]);
    }

    protected function resolveCountryCode(Project $project): ?string
    {
        // Try org country, then creator country
        $countries = config('countries', []);
        $pays = $project->creator?->pays;

        if (!$pays) return null;

        // Check if pays is already a code
        if (strlen($pays) === 2 && ctype_alpha($pays)) {
            return strtoupper($pays);
        }

        // Search by name in countries config
        foreach ($countries as $code => $info) {
            if (
                mb_strtolower($info['name_fr'] ?? '') === mb_strtolower($pays) ||
                mb_strtolower($info['name_en'] ?? '') === mb_strtolower($pays) ||
                mb_strtolower($code) === mb_strtolower($pays)
            ) {
                return strtoupper($code);
            }
        }

        return null;
    }
}

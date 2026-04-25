<?php

namespace App\Traits;

use App\Models\Indicator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait SyncsIndicators
{
    /**
     * Synchronise les indicateurs d'un model (LogicalFramework, SpecificObjective, Result).
     * Cree les nouveaux, met a jour les existants, supprime les absents.
     */
    protected function syncIndicators(Model $model, array $indicatorsData): void
    {
        $existingIds = $model->indicators()->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($indicatorsData as $index => $data) {
            if (empty(trim($data['description'] ?? ''))) {
                continue;
            }

            $payload = [
                'description' => $data['description'],
                'verification_source' => $data['verification_source'] ?? null,
                'assumption' => $data['assumption'] ?? null,
                'baseline_value' => $data['baseline_value'] ?? null,
                'target_value' => $data['target_value'] ?? null,
                'order' => $index,
            ];

            if (!empty($data['id']) && in_array($data['id'], $existingIds)) {
                Indicator::where('id', $data['id'])->update($payload);
                $submittedIds[] = $data['id'];
            } else {
                $indicator = $model->indicators()->create(array_merge($payload, [
                    'id' => (string) Str::uuid(),
                ]));
                $submittedIds[] = $indicator->id;
            }
        }

        $toDelete = array_diff($existingIds, $submittedIds);
        if (!empty($toDelete)) {
            Indicator::whereIn('id', $toDelete)->delete();
        }
    }
}

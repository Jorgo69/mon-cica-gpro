<?php

namespace App\Livewire\V1\Project;

use App\Livewire\Traits\WithToastNotifications;
use App\Models\Indicator;
use App\Models\IndicatorMeasurement;
use App\Models\Project;
use App\Services\Queries\LogframeQueryService;
use Livewire\Component;

class IndicatorTrackingLivewire extends Component
{
    use WithToastNotifications;

    public string $projectId;

    // Measurement form
    public ?string $activeIndicatorId = null;
    public string $measurementValue = '';
    public string $measurementComment = '';
    public string $measurementDate = '';

    // History modal
    public ?string $historyIndicatorId = null;

    public function mount(string $projectId)
    {
        $this->projectId = $projectId;
        $this->measurementDate = now()->format('Y-m-d');
    }

    public function openMeasureForm(string $indicatorId)
    {
        $this->activeIndicatorId = $indicatorId;
        $this->reset('measurementValue', 'measurementComment');
        $this->measurementDate = now()->format('Y-m-d');
    }

    public function saveMeasurement()
    {
        $this->validate([
            'measurementValue' => 'required|string|max:50',
            'measurementDate' => 'required|date',
            'measurementComment' => 'nullable|string|max:500',
        ]);

        $indicator = Indicator::findOrFail($this->activeIndicatorId);

        IndicatorMeasurement::create([
            'indicator_id' => $indicator->id,
            'organization_id' => $indicator->organization_id,
            'measured_by_user_id' => auth()->id(),
            'value' => $this->measurementValue,
            'comment' => $this->measurementComment ?: null,
            'measured_at' => $this->measurementDate,
        ]);

        // Update current_value on indicator
        $indicator->update(['current_value' => $this->measurementValue]);

        $this->reset('activeIndicatorId', 'measurementValue', 'measurementComment');
        $this->notifyToast('success', __('indicators.measurement_saved'));
    }

    public function showHistory(string $indicatorId)
    {
        $this->historyIndicatorId = $indicatorId;
    }

    public function deleteMeasurement(string $measurementId)
    {
        $measurement = IndicatorMeasurement::findOrFail($measurementId);
        $indicator = $measurement->indicator;
        $measurement->delete();

        // Update current_value to latest remaining measurement
        $latest = $indicator->measurements()->first();
        $indicator->update(['current_value' => $latest?->value]);

        $this->notifyToast('success', __('common.deleted_successfully'));
    }

    public function render()
    {
        $project = LogframeQueryService::forProject($this->projectId)->project();

        $indicators = collect();
        if ($project?->logicalFramework) {
            $lf = $project->logicalFramework;
            $lf->loadMissing('indicatorItems.measurements.measuredBy');

            foreach ($lf->indicatorItems as $ind) {
                $ind->_level = __('shared.general_objective');
                $ind->_parent = $lf->general_objective ?? '';
                $indicators->push($ind);
            }

            foreach ($lf->specificObjectives as $so) {
                $so->loadMissing('indicatorItems.measurements.measuredBy');
                foreach ($so->indicatorItems as $ind) {
                    $ind->_level = __('shared.specific_objective');
                    $ind->_parent = $so->description ?? '';
                    $indicators->push($ind);
                }

                foreach ($so->results as $result) {
                    $result->loadMissing('indicatorItems.measurements.measuredBy');
                    foreach ($result->indicatorItems as $ind) {
                        $ind->_level = __('shared.result');
                        $ind->_parent = $result->description ?? '';
                        $indicators->push($ind);
                    }
                }
            }
        }

        $historyIndicator = $this->historyIndicatorId
            ? Indicator::with('measurements.measuredBy')->find($this->historyIndicatorId)
            : null;

        return view('livewire.v1.project.indicator-tracking-livewire', [
            'indicators' => $indicators,
            'historyIndicator' => $historyIndicator,
        ]);
    }
}

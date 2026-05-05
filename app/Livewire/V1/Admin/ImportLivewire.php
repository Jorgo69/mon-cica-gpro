<?php

namespace App\Livewire\V1\Admin;

use App\Exports\ImportTemplateExport;
use App\Imports\ActivityImport;
use App\Imports\ProjectImport;
use App\Livewire\Traits\WithToastNotifications;
use App\Models\Result;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportLivewire extends Component
{
    use WithFileUploads, WithToastNotifications;

    public string $step = 'upload'; // upload, preview, result
    public string $importType = 'projects'; // projects, activities
    public ?string $resultId = null; // for activity import
    public $file;

    public array $preview = [];
    public array $importErrors = [];
    public int $importedCount = 0;
    public int $errorCount = 0;

    protected function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            'importType' => 'required|in:projects,activities',
        ];
    }

    public function updatedImportType(): void
    {
        $this->reset(['file', 'preview', 'importErrors', 'step', 'resultId']);
        $this->step = 'upload';
    }

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filename = "template-{$this->importType}.xlsx";
        return Excel::download(new ImportTemplateExport($this->importType), $filename);
    }

    public function parseFile(): void
    {
        $this->validate();

        try {
            $rows = $this->readExcel();

            if ($rows->isEmpty()) {
                $this->notifyToast('error', __('import.empty_file'));
                return;
            }

            $importer = $this->getImporter();
            $this->preview = $importer->preview($rows);
            $this->importErrors = $importer->getErrors();
            $this->step = 'preview';
        } catch (\Exception $e) {
            $this->notifyToast('error', __('import.parse_error') . ': ' . $e->getMessage());
        }
    }

    public function confirmImport(): void
    {
        try {
            $rows = $this->readExcel();
            $importer = $this->getImporter();
            $result = $importer->import($rows);

            $this->importedCount = $result['imported'];
            $this->importErrors = $result['importErrors'];
            $this->errorCount = count($this->importErrors);
            $this->step = 'result';

            if ($this->importedCount > 0) {
                $this->notifyToast('success', __('import.success', ['count' => $this->importedCount]));
            }
        } catch (\Exception $e) {
            $this->notifyToast('error', __('import.import_error') . ': ' . $e->getMessage());
        }
    }

    public function resetImport(): void
    {
        $this->reset(['file', 'preview', 'importErrors', 'importedCount', 'errorCount', 'resultId']);
        $this->step = 'upload';
    }

    protected function readExcel(): Collection
    {
        $path = $this->file->getRealPath();
        $data = Excel::toCollection(null, $path)->first();

        // Skip header row
        return $data->slice(1)->values();
    }

    protected function getImporter(): ProjectImport|ActivityImport
    {
        $user = auth()->user();
        $orgId = $user->organization_id;

        if ($this->importType === 'activities') {
            return new ActivityImport(
                resultId: $this->resultId ?? '',
                organizationId: $orgId,
                creatorUserId: $user->id,
            );
        }

        return new ProjectImport(
            organizationId: $orgId,
            creatorUserId: $user->id,
        );
    }

    public function render()
    {
        $results = collect();
        if ($this->importType === 'activities' && auth()->user()->organization_id) {
            $results = Result::whereHas('specificObjective.logicalFramework.project', function ($q) {
                $q->where('organization_id', auth()->user()->organization_id);
            })->with('specificObjective.logicalFramework.project:id,title')
              ->limit(50)
              ->get();
        }

        return view('livewire.v1.admin.import-livewire', [
            'availableResults' => $results,
        ]);
    }
}

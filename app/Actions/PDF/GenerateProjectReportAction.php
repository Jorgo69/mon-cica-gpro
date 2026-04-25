<?php

namespace App\Actions\PDF;

use App\Models\Project;
use App\Services\PDF\PDFTemplateManager;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateProjectReportAction
{
    public function __construct(
        protected PDFTemplateManager $templateManager
    ) {}

    /**
     * Generate a PDF report for a project.
     *
     * @param string $projectId
     * @param string|null $templateKey
     * @return string Path to the generated PDF
     */
    public function execute(string $projectId, ?string $templateKey = null): string
    {
        $project = Project::with([
            'creator',
            'projectType.dynamicFields',
            'logicalFramework.indicators',
            'logicalFramework.specificObjectives.indicators',
            'logicalFramework.specificObjectives.results.indicators',
            'logicalFramework.specificObjectives.results.activities.subActivities',
            'budgets.responsibleUser',
            'documents',
        ])->findOrFail($projectId);

        // Select template
        $template = $templateKey 
            ? $this->templateManager->getAvailableTemplates()->get($templateKey)
            : $this->templateManager->getTemplateForOrganization($project->organization_id);

        if (!$template) {
            throw new \Exception("Modèle PDF non trouvé : {$templateKey}");
        }

        // Prepare Dynamic Fields
        $dynamicFormFields = [];
        if ($project->projectType) {
            $dynamicFormFields = $project->projectType->dynamicFields()
                ->orderBy('order')
                ->get()
                ->groupBy('section')
                ->toArray();
        }

        // Render HTML
        $html = view($template['view'], [
            'project' => $project,
            'dynamicFormFields' => $dynamicFormFields,
        ])->render();

        // Define output path
        $fileName = 'Rapport_' . Str::slug($project->title) . '_' . now()->format('YmdHis') . '.pdf';
        $directory = 'exports/pdf';
        
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $path = storage_path('app/public/' . $directory . '/' . $fileName);

        // Generate PDF via Browsershot
        Browsershot::html($html)
            ->format('A4')
            ->setChromePath('/snap/bin/chromium') // As configured on the system
            ->noSandbox()
            ->margins(0, 0, 0, 0)
            ->waitUntilNetworkIdle() // Ensure all assets are loaded
            ->save($path);

        return $path;
    }
}

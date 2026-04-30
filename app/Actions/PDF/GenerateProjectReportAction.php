<?php

namespace App\Actions\PDF;

use PdfStudio\Laravel\Facades\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateProjectReportAction
{
    public function execute(string $projectId, ?string $templateKey = null): string
    {
        $project = \App\Services\Queries\LogframeQueryService::forProject($projectId)->project();

        if (!$project) {
            throw new \Exception("Projet non trouve : {$projectId}");
        }

        // Resolve template
        $templateKey = $templateKey ?? 'classic';
        $templates = config('gpro.pdf.templates', []);
        $templateConfig = $templates[$templateKey] ?? null;

        if (!$templateConfig) {
            throw new \Exception("Modele PDF non trouve : {$templateKey}");
        }

        // Resolve view based on active PDF driver
        $driver = config('pdf-studio.default_driver', 'dompdf');
        $driverKey = ($driver === 'chromium') ? 'browsershot' : 'dompdf';
        $viewName = is_array($templateConfig['view'])
            ? ($templateConfig['view'][$driverKey] ?? $templateConfig['view']['dompdf'])
            : $templateConfig['view'];

        // Prepare dynamic fields
        $dynamicFormFields = [];
        if ($project->projectType) {
            $dynamicFormFields = $project->projectType->dynamicFields()
                ->orderBy('order')
                ->get()
                ->groupBy('section')
                ->toArray();
        }

        // Output path
        $fileName = 'Rapport_' . Str::slug($project->title) . '_' . now()->format('YmdHis') . '.pdf';
        $directory = 'exports/pdf';

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $path = storage_path('app/public/' . $directory . '/' . $fileName);

        // Generate PDF via PDF Studio
        $relativePath = $directory . '/' . $fileName;
        Pdf::view($viewName)
            ->data([
                'project' => $project,
                'dynamicFormFields' => $dynamicFormFields,
            ])
            ->save($relativePath);

        return storage_path('app/' . $relativePath);
    }
}

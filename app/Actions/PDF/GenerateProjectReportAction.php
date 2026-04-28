<?php

namespace App\Actions\PDF;

use App\Services\PDF\PdfDriverFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateProjectReportAction
{
    public function execute(string $projectId, ?string $templateKey = null, ?string $driver = null): string
    {
        $project = \App\Services\Queries\LogframeQueryService::forProject($projectId)->project();

        if (!$project) {
            throw new \Exception("Projet non trouve : {$projectId}");
        }

        // Resolve driver and template
        $driverName = $driver ?? config('gpro.pdf.driver', 'dompdf');
        $templateKey = $templateKey ?? 'classic';
        $templates = config('gpro.pdf.templates', []);
        $templateConfig = $templates[$templateKey] ?? null;

        if (!$templateConfig) {
            throw new \Exception("Modele PDF non trouve : {$templateKey}");
        }

        // Resolve view based on driver
        $viewName = is_array($templateConfig['view'])
            ? ($templateConfig['view'][$driverName] ?? $templateConfig['view']['dompdf'])
            : $templateConfig['view'];

        // Prepare dynamic fields
        $dynamicFormFields = [];
        if ($project->projectType && $project->projectType->relationLoaded('dynamicFields')) {
            $dynamicFormFields = $project->projectType->dynamicFields
                ->groupBy('section')
                ->toArray();
        } elseif ($project->projectType) {
            $dynamicFormFields = $project->projectType->dynamicFields()
                ->orderBy('order')
                ->get()
                ->groupBy('section')
                ->toArray();
        }

        // Render HTML
        $html = view($viewName, [
            'project' => $project,
            'dynamicFormFields' => $dynamicFormFields,
        ])->render();

        // Output path
        $fileName = 'Rapport_' . Str::slug($project->title) . '_' . now()->format('YmdHis') . '.pdf';
        $directory = 'exports/pdf';

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $path = storage_path('app/public/' . $directory . '/' . $fileName);

        // Generate PDF via configured driver
        $pdfDriver = PdfDriverFactory::make($driverName);
        $pdfDriver->generate($html, $path);

        return $path;
    }
}

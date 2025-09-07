<?php

namespace App\Http\Controllers\VBeta\PDF;

use Storage;
use App\Models\Project;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use App\Http\Controllers\Controller;

class ProjectExportController extends Controller
{
    // public function exportPdf(string $projectId)
    // {
    //      $project = Project::with([
    //         'creator',
    //     'projectType',
    //     'projectContext',
    //     'logicalFramework.specificObjectives.results.activities.subActivities',
    //     'budgets.responsibleUser',
    //     'documents',
    //         ])->findOrFail($projectId);

    
    // // 1. Construire ton HTML avec Blade
    // $html = view('v_beta.pdf.index', [
    //     'project' => $project,
    // ])->render();

    // // 2. Définir chemin de sortie
    // $fileName = 'projet_'.$project->id.'.pdf';
    // $path = storage_path('app/public/exports/'.$fileName);

    // // S'assurer que le dossier existe
    // if (!Storage::disk('public')->exists('exports')) {
    //     \Storage::disk('public')->makeDirectory('exports');
    // }

    // // 3. Génération PDF avec Chromium Snap
    // Browsershot::html($html)
    //     ->format('A4')
    //     ->setChromePath('/snap/bin/chromium')
    //     ->noSandbox() // important pour Snap
    //     ->save($path);

    // // 4. Télécharger le fichier
    // return response()->download($path)->deleteFileAfterSend(true);
    // }

    public function exportPdf(string $projectId)
    {
         $project = Project::with([
            'creator',
            'projectType',
            'projectContext',
            'logicalFramework.specificObjectives.results.activities.subActivities',
            'budgets.responsibleUser',
            'documents',
            ])->findOrFail($projectId);

        // Charge les définitions des champs dynamiques pour l'affichage
        if ($project->projectType) {
             $dynamicFormFields = $project->projectType->dynamicFields()
                ->orderBy('order')
                ->get()
                ->groupBy('section')
                ->toArray();
        }

    
    // 1. Construire ton HTML avec Blade
    return view('v_beta.pdf.index', [
        'project' => $project,
        'dynamicFormFields' => $dynamicFormFields
    ]);

    
    }
}

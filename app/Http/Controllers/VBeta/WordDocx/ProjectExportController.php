<?php

namespace App\Http\Controllers\VBeta\WordDocx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

class ProjectExportController extends Controller
{
    // public function exportWord($id)
    // {
    //     $project = Project::with(['projectContext'])->findOrFail($id);

    //     $phpWord = new PhpWord();
    //     $section = $phpWord->addSection();

    //     // Exemple de mise en forme
    //     $section->addTitle('NOTE DE CONCEPT - PROJET REGIONAL', 1);
    //     $section->addText("Titre du projet : " . $project->title, ['bold' => true]);
    //     $section->addText("Code projet : " . $project->project_code);
    //     $section->addTextBreak();

    //     // CONTEXTE
    //     $section->addTitle('I - CONTEXTE DU PROJET', 2);
    //     $section->addText(strip_tags($project->projectContext->context_description ?? 'Non renseigné'));

    //     // OBJECTIFS
    //     $section->addTitle('II - OBJECTIFS', 2);
    //     $section->addText("Objectif général : " . ($project->general_objectives ?? 'Non renseigné'));
    //     $section->addText("Analyse du problème : " . ($project->problem_analysis ?? 'Non renseigné'));
    //     $section->addText("Stratégie : " . ($project->strategy ?? 'Non renseigné'));
    //     $section->addText("Justification : " . ($project->justification ?? 'Non renseigné'));

    //     // SAUVEGARDE
    //     $fileName = 'projet_' . $project->id . '.docx';
    //     $path = storage_path("app/public/{$fileName}");
    //     $phpWord->save($path, 'Word2007', true);

    //     return response()->download($path)->deleteFileAfterSend(true);
    // }

    // public function exportWord($projectId)
    // {
    //     $project = Project::with('projectContext')->findOrFail($projectId);

    //     // Charger le template Word (.docx) basé sur ton modèle
    //     $templatePath = storage_path('app/templates/modele_projet.docx');
    //     $template = new TemplateProcessor($templatePath);

    //     // Remplacer les variables par tes données
    //     $template->setValue('titre', $project->title);
    //     $template->setValue('code', $project->project_code);
    //     $template->setValue('contexte', $project->projectContext->context_description ?? '');
    //     $template->setValue('analyse', $project->problem_analysis ?? '');
    //     $template->setValue('strategie', $project->strategy ?? '');
    //     $template->setValue('justification', $project->justification ?? '');
    //     // … tu continues pour toutes les sections du modèle

    //     // Sauvegarder temporairement
    //     $fileName = 'projet_'.$project->id.'.docx';
    //     $savePath = storage_path('app/public/exports/'.$fileName);
    //     $template->saveAs($savePath);

    //     // Télécharger
    //     return response()->download($savePath)->deleteFileAfterSend(true);
    // }


//     public function exportWord($projectId)
// {
//     $project = Project::with('projectContext')->findOrFail($projectId);

//     $templatePath = storage_path('app/templates/modele_projet.docx');
//     $template = new TemplateProcessor($templatePath);

//     $template->setValue('titre', $project->title);
//     $template->setValue('code', $project->project_code);
//     $template->setValue('contexte', $project->projectContext->context_description ?? '');
//     $template->setValue('analyse', $project->problem_analysis ?? '');
//     $template->setValue('strategie', $project->strategy ?? '');
//     $template->setValue('justification', $project->justification ?? '');

//     // Dossier cible
//     $exportDir = storage_path('app/public/exports');
//     if (!file_exists($exportDir)) {
//         mkdir($exportDir, 0777, true);
//     }

//     $fileName = 'projet_'.$project->id.'.docx';
//     $savePath = $exportDir.'/'.$fileName;

//     $template->saveAs($savePath);

//     return response()->download($savePath)->deleteFileAfterSend(true);
// }
// et fais moi un retour tres bref

// Pas besoin de beaucoup bavarder


/**
     * Génère un document Word à partir d'un projet de la base de données.
     *
     * @param  string  $projectId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportWord(string $projectId)
{
    // Charger projet avec toutes ses relations
    $project = Project::with([
        'creator',
        'projectType',
        'projectContext',
        'logicalFramework.specificObjectives.results.activities.subActivities',
        'budgets.responsibleUser',
        'documents',
    ])->findOrFail($projectId);

    // Charger le modèle Word
    $templatePath = storage_path('app/templates/modele_projet.docx');
    $template = new TemplateProcessor($templatePath);

    // 1. Champs simples
    $template->setValue('project_title', $project->title);
    $template->setValue('project_code', $project->project_code);
    $template->setValue('short_title', $project->short_title ?? '');
    $template->setValue('status', $project->status);
    $template->setValue('start_date', $project->start_date?->format('d/m/Y') ?? '');
    $template->setValue('end_date', $project->end_date?->format('d/m/Y') ?? '');
    $template->setValue('description', $project->description ?? '');
    $template->setValue('general_objectives', $project->general_objectives ?? '');
    $template->setValue('problem_analysis', $project->problem_analysis ?? '');
    $template->setValue('strategy', $project->strategy ?? '');
    $template->setValue('justification', $project->justification ?? '');
    $template->setValue('context', $project->projectContext->context_description ?? '');

    // 2. Cadre logique
    if ($project->logicalFramework) {
        $template->setValue('general_objective', $project->logicalFramework->general_objective ?? '');
        $template->setValue('general_obj_indicators', $project->logicalFramework->general_obj_indicators ?? '');
        $template->setValue('general_obj_sources', $project->logicalFramework->general_obj_verification_sources ?? '');
        $template->setValue('assumptions', $project->logicalFramework->assumptions ?? '');

        // Objectifs spécifiques
        if ($project->logicalFramework->specificObjectives->count() > 0) {
            $template->cloneRow('specific_obj_desc', $project->logicalFramework->specificObjectives->count());
            foreach ($project->logicalFramework->specificObjectives as $i => $so) {
                $n = $i + 1;
                $template->setValue("specific_obj_desc#{$n}", $so->description);
                $template->setValue("specific_obj_indicators#{$n}", $so->indicators ?? '');
                $template->setValue("specific_obj_assumptions#{$n}", $so->assumptions ?? '');

                // Résultats
                if ($so->results->count() > 0) {
                    $template->cloneRow("result_desc#{$n}", $so->results->count());
                    foreach ($so->results as $j => $res) {
                        $m = $j + 1;
                        $template->setValue("result_desc#{$n}#{$m}", $res->description);

                        // Activités
                        if ($res->activities->count() > 0) {
                            $template->cloneRow("activity_desc#{$n}#{$m}", $res->activities->count());
                            foreach ($res->activities as $k => $act) {
                                $a = $k + 1;
                                $template->setValue("activity_desc#{$n}#{$m}#{$a}", $act->description);
                                $template->setValue("activity_responsible#{$n}#{$m}#{$a}", $act->responsibleUser->name ?? 'N/A');
                                $template->setValue("activity_status#{$n}#{$m}#{$a}", $act->status ?? '');
                                $template->setValue("activity_budget#{$n}#{$m}#{$a}", $act->budget ?? 0);
                            }
                        }
                    }
                }
            }
        }
    }

    // 3. Budgets
    if ($project->budgets->count() > 0) {
        $template->cloneRow('budget_desc', $project->budgets->count());
        foreach ($project->budgets as $i => $budget) {
            $n = $i + 1;
            $template->setValue("budget_desc#{$n}", $budget->description);
            $template->setValue("budget_qty#{$n}", $budget->quantity ?? '');
            $template->setValue("budget_unit_cost#{$n}", $budget->unit_cost ?? 0);
            $template->setValue("budget_total_cost#{$n}", $budget->total_cost ?? 0);
            $template->setValue("budget_category#{$n}", $budget->category ?? '');
            $template->setValue("budget_responsible#{$n}", $budget->responsibleUser->name ?? 'N/A');
        }
    }

    // 4. Documents
    if ($project->documents->count() > 0) {
        $template->cloneRow('doc_name', $project->documents->count());
        foreach ($project->documents as $i => $doc) {
            $n = $i + 1;
            $template->setValue("doc_name#{$n}", $doc->file_name);
            $template->setValue("doc_path#{$n}", $doc->file_path);
        }
    }

    // 5. Export
    $fileName = 'projet_'.$project->id.'.docx';
    $savePath = storage_path('app/public/exports/'.$fileName);

    if (!Storage::disk('public')->exists('exports')) {
        Storage::disk('public')->makeDirectory('exports');
    }

    $template->saveAs($savePath);

    return response()->download($savePath)->deleteFileAfterSend(true);
}


}

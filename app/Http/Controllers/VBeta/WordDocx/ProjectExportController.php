<?php

namespace App\Http\Controllers\VBeta\WordDocx;

use App\Models\Project;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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
    // public function exportWord(string $projectId)
    // {
    //     // Charger projet avec toutes ses relations
    //     $project = Project::with([
    //         'creator',
    //         'projectType',
    //         'projectContext',
    //         'logicalFramework.specificObjectives.results.activities.subActivities',
    //         'budgets.responsibleUser',
    //         'documents',
    //     ])->findOrFail($projectId);

    //     // Charger le modèle Word
    //     $templatePath = storage_path('app/templates/modele_projet.docx');
    //     $template = new TemplateProcessor($templatePath);

    //     // 1. Champs simples
    //     $template->setValue('project_title', $project->title);
    //     $template->setValue('project_code', $project->project_code);
    //     $template->setValue('short_title', $project->short_title ?? '');
    //     $template->setValue('status', $project->status);
    //     $template->setValue('start_date', $project->start_date?->format('d/m/Y') ?? '');
    //     $template->setValue('end_date', $project->end_date?->format('d/m/Y') ?? '');
    //     $template->setValue('description', $project->description ?? '');
    //     $template->setValue('general_objectives', $project->general_objectives ?? '');
    //     $template->setValue('problem_analysis', $project->problem_analysis ?? '');
    //     $template->setValue('strategy', $project->strategy ?? '');
    //     $template->setValue('justification', $project->justification ?? '');
    //     $template->setValue('context', $project->projectContext->context_description ?? '');

    //     // 2. Cadre logique
    //     if ($project->logicalFramework) {
    //         $template->setValue('general_objective', $project->logicalFramework->general_objective ?? '');
    //         $template->setValue('general_obj_indicators', $project->logicalFramework->general_obj_indicators ?? '');
    //         $template->setValue('general_obj_sources', $project->logicalFramework->general_obj_verification_sources ?? '');
    //         $template->setValue('assumptions', $project->logicalFramework->assumptions ?? '');

    //         // Objectifs spécifiques
    //         if ($project->logicalFramework->specificObjectives->count() > 0) {
    //             $template->cloneRow('specific_obj_desc', $project->logicalFramework->specificObjectives->count());
    //             foreach ($project->logicalFramework->specificObjectives as $i => $so) {
    //                 $n = $i + 1;
    //                 $template->setValue("specific_obj_desc#{$n}", $so->description);
    //                 $template->setValue("specific_obj_indicators#{$n}", $so->indicators ?? '');
    //                 $template->setValue("specific_obj_assumptions#{$n}", $so->assumptions ?? '');

    //                 // Résultats
    //                 if ($so->results->count() > 0) {
    //                     $template->cloneRow("result_desc#{$n}", $so->results->count());
    //                     foreach ($so->results as $j => $res) {
    //                         $m = $j + 1;
    //                         $template->setValue("result_desc#{$n}#{$m}", $res->description);

    //                         // Activités
    //                         if ($res->activities->count() > 0) {
    //                             $template->cloneRow("activity_desc#{$n}#{$m}", $res->activities->count());
    //                             foreach ($res->activities as $k => $act) {
    //                                 $a = $k + 1;
    //                                 $template->setValue("activity_desc#{$n}#{$m}#{$a}", $act->description);
    //                                 $template->setValue("activity_responsible#{$n}#{$m}#{$a}", $act->responsibleUser->name ?? 'N/A');
    //                                 $template->setValue("activity_status#{$n}#{$m}#{$a}", $act->status ?? '');
    //                                 $template->setValue("activity_budget#{$n}#{$m}#{$a}", $act->budget ?? 0);
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     // 3. Budgets
    //     if ($project->budgets->count() > 0) {
    //         $template->cloneRow('budget_desc', $project->budgets->count());
    //         foreach ($project->budgets as $i => $budget) {
    //             $n = $i + 1;
    //             $template->setValue("budget_desc#{$n}", $budget->description);
    //             $template->setValue("budget_qty#{$n}", $budget->quantity ?? '');
    //             $template->setValue("budget_unit_cost#{$n}", $budget->unit_cost ?? 0);
    //             $template->setValue("budget_total_cost#{$n}", $budget->total_cost ?? 0);
    //             $template->setValue("budget_category#{$n}", $budget->category ?? '');
    //             $template->setValue("budget_responsible#{$n}", $budget->responsibleUser->name ?? 'N/A');
    //         }
    //     }

    //     // 4. Documents
    //     if ($project->documents->count() > 0) {
    //         $template->cloneRow('doc_name', $project->documents->count());
    //         foreach ($project->documents as $i => $doc) {
    //             $n = $i + 1;
    //             $template->setValue("doc_name#{$n}", $doc->file_name);
    //             $template->setValue("doc_path#{$n}", $doc->file_path);
    //         }
    //     }

    //     // 5. Export
    //     $fileName = 'projet_'.$project->id.'.docx';
    //     $savePath = storage_path('app/public/exports/'.$fileName);

    //     if (!Storage::disk('public')->exists('exports')) {
    //         Storage::disk('public')->makeDirectory('exports');
    //     }

    //     $template->saveAs($savePath);

    //     return response()->download($savePath)->deleteFileAfterSend(true);
    // }



    // public function generateWord()
    // {
    //     // Exemple : tu veux injecter le titre du projet
    //     // $text = "Titre du projet: " . $this->projectTitle;
    //     $text = "Titre du projet: ";

    //     // Appel de l’API
    //     $response = Http::withHeaders([
    //         'Accept' => 'application/json',
    //         'Content-Type' => 'application/json',
    //     ])->post('https://apisamedi.onrender.com/generator/word', [
    //         'text' => $text,
    //     ]);

    //     // Vérification
    //     if ($response->successful()) {
    //         // Le contenu binaire du docx est dans le body
    //         $fileName = 'projet_' . uniqid() . '.docx';
    //         $savePath = storage_path('app/public/exports/' . $fileName);

    //         // S'assurer que le dossier existe
    //         if (!\Storage::disk('public')->exists('exports')) {
    //             \Storage::disk('public')->makeDirectory('exports');
    //         }

    //         file_put_contents($savePath, $response->body());

    //         return response()->download($savePath)->deleteFileAfterSend(true);
    //     }

    //     return response()->json(['error' => 'Échec de la génération'], 500);
    // }


    public function exportWordViaApi(string $projectId)
    {
        // 1. Charger le projet avec relations nécessaires
        $project = \App\Models\Project::with([
            'creator',
            'projectType',
            'projectContext',
            'logicalFramework.specificObjectives.results.activities.subActivities',
            'documents',
        ])->findOrFail($projectId);

        // 2. Construire le HTML via une vue Blade (pratique & maintenable)
        $html = view('v_beta.word_template', compact('project'))->render();

        // 3. Appel API externe (apisamedi)
        $endpoint = 'https://apisamedi.onrender.com/generator/word';

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($endpoint, [
                    'text' => $html,
                ]);
        } catch (\Throwable $e) {
            Log::error('Word API request error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur réseau lors de la génération du document.');
        }

        if (!in_array($response->status(), [200, 201])) {
            Log::error('Word API returned non-success', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return back()->with('error', 'La génération a échoué (code ' . $response->status() . ').');
        }

        // 4. Sauvegarder le fichier reçu
        $fileName = 'projet_' . $project->id . '_' . time() . '.docx';
        $exportsDir = storage_path('app/public/exports');

        if (!Storage::disk('public')->exists('exports')) {
            Storage::disk('public')->makeDirectory('exports');
        }

        $savePath = $exportsDir . '/' . $fileName;
        file_put_contents($savePath, $response->body());

        // 5. Retourner le téléchargement et supprimer après envoi
        return response()->download($savePath)->deleteFileAfterSend(true);
    }



}

<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('v_beta')->group(function () {
    // Proposition de projet
    Route::prefix('/creator-proposal')->name('creator.proposal.project.')->group(function () {
        Route::view('/proposal-project/create', 'pages.project.proposal-form')->name('create');
        Route::view('/proposal-project/{projectId}/edit', 'pages.project.proposal-form')->name('edit');
    });

    // Alias pour project.create (utilisé dans les vues existantes)
    Route::get('/v_beta/projects/create-alias', function() {
        return redirect()->route('creator.proposal.project.create');
    })->middleware(['auth'])->name('project.create');

    // Templates
    Route::view('/project-templates', 'v_beta.project-templates')->name('project.templates');

    // Gestion Centrale Projet
    Route::view('/project-list', 'pages.project.list')->name('project.list');
    Route::get('/projects/{projectId}/show', [App\Http\Controllers\VBeta\ProjectShowController::class, 'index'])->name('project.show');
    Route::view('/project-dashboard/{projectId}/management', 'pages.project.dashboard')->name('project.dashboard');

    // Exports
    Route::get('/projects/{id}/export-word', [App\Http\Controllers\VBeta\WordDocx\ProjectExportController::class, 'exportWord'])->name('projects.export.word');
    Route::get('/projects/{id}/export-pdf', [App\Http\Controllers\VBeta\PDF\ProjectExportController::class, 'exportPdf'])->name('projects.export.pdf');
    Route::get('/generator/word/{id}', [App\Http\Controllers\VBeta\WordDocx\ProjectExportController::class, 'exportWordViaApi'])->name('project.export.word');

    // Export Excel
    Route::get('/projects/{id}/export-excel/{type?}', function (string $id, string $type = 'full') {
        $project = \App\Models\Project::findOrFail($id);
        $filename = \Illuminate\Support\Str::slug($project->title) . '-' . $type . '.xlsx';

        return match ($type) {
            'activities' => \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProjectActivitiesExport($project), $filename),
            'budget' => \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProjectBudgetExport($project), $filename),
            'indicators' => \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProjectIndicatorsExport($project), $filename),
            default => \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProjectFullExport($project), $filename),
        };
    })->name('projects.export.excel');
});

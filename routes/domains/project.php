<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('v_beta')->group(function () {
    // Proposition de projet
    Route::prefix('/creator-proposal')->name('creator.proposal.project.')->group(function () {
        Route::view('/proposal-project/create', 'v_beta.proposal-project.form')->name('create');
        Route::view('/proposal-project/{projectId}/edit', 'v_beta.proposal-project.form')->name('edit');
    });

    // Alias pour project.create (utilisé dans les vues existantes)
    Route::get('/v_beta/projects/create-alias', function() {
        return redirect()->route('creator.proposal.project.create');
    })->middleware(['auth'])->name('project.create');

    // Gestion Centrale Projet
    Route::view('/project-list', 'v_beta.project-list')->name('project.list');
    Route::get('/projects/{projectId}/show', [App\Http\Controllers\VBeta\ProjectShowController::class, 'index'])->name('project.show');
    Route::view('/project-dashboard/{projectId}/management', 'v_beta.project-dashboard')->name('project.dashboard');

    // Exports
    Route::get('/projects/{id}/export-word', [App\Http\Controllers\VBeta\WordDocx\ProjectExportController::class, 'exportWord'])->name('projects.export.word');
    Route::get('/projects/{id}/export-pdf', [App\Http\Controllers\VBeta\PDF\ProjectExportController::class, 'exportPdf'])->name('projects.export.pdf');
    Route::get('/generator/word/{id}', [App\Http\Controllers\VBeta\WordDocx\ProjectExportController::class, 'exportWordViaApi'])->name('project.export.word');
});

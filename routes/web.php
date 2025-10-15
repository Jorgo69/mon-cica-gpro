<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



    

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [App\Http\Controllers\VBeta\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->prefix('v_beta')->group(function () {
// Project
Route::view('/project-list', 'v_beta.project-list')->name('project.list');
// Route::view('/project-create', 'v_beta.project-design')->name('project.create');
// Route::view('/project-edit/{projectId}', 'v_beta.project-design-edit')->name('project.edit');

Route::view('/project-dashboard/{projectId}/management', 'v_beta.project-dashboard')->name('project.dashboard');

// End Project

    // Admin - IT
    Route::prefix('/admin-it')->name('admin.it.')->group(function () {

        Route::view('/project/list', 'v_beta.admin.project.index')->name('project.list');

        Route::view('/type_of_project', 'v_beta.admin.type_of_project.index')->middleware(['auth'])->name('type.of.project');

        Route::view('/type_of_project/{projectTypeId}/show', 'v_beta.admin.type_of_project.show')->middleware(['auth'])->name('type.of.project.show');

        // Route pour afficher le formulaire de création
        Route::view('/project-types/create', 'v_beta.admin.type_of_project.form')->name('project.types.create');

        // Route pour afficher le formulaire d'édition
        Route::view('/project-types/{projectTypeId}/edit', 'v_beta.admin.type_of_project.form')->name('project.types.edit');

        // Gestion Membres
        Route::view('/members/list', 'v_beta.admin.member.index')->name('member.list');

        // Gestion Categories
        Route::view('/categories/list', 'v_beta.admin.category.index')->name('category.list');

        // Gestion Corbeille
        Route::view('/trash/management', 'v_beta.admin.trash.index')->name('trash.management');

    });

    // End Admin - IT

    // Proposition de projet Start


    Route::prefix('/creator-proposal')->name('creator.proposal.project.')->group(function () {

        // Route pour afficher le formulaire de création
        Route::view('/proposal-project/create', 'v_beta.proposal-project.form')->name('create');

        // Route pour afficher le formulaire d'édition
        Route::view('/proposal-project/{projectId}/edit', 'v_beta.proposal-project.form')->name('edit');
    });


    // Route pour afficher les details
    Route::get('/projects/{projectId}/show', [App\Http\Controllers\VBeta\ProjectShowController::class, 'index'])->name('project.show');

    // Proposition de projet End

    // Ressources
    Route::view('/resource/management', 'v_beta.resource.index')->name('resource.index');
    // End Ressource

    // Activities
    Route::view('/activity/list', 'v_beta.activity.index')->name('activity.index');
    Route::view('/activity/{activity}/management', 'v_beta.activity.management')->name('activity.management');
    // End Activities


    // Docx Word

    Route::get('/projects/{id}/export-word', [App\Http\Controllers\VBeta\WordDocx\ProjectExportController::class, 'exportWord'])
        ->name('projects.export.word');

    // End Docx Word

    // PDF

    Route::get('/projects/{id}/export-pdf', [App\Http\Controllers\VBeta\PDF\ProjectExportController::class, 'exportPdf'])
        ->name('projects.export.pdf');

    // End PDF



    Route::get('/generator/word/{id}', [App\Http\Controllers\VBeta\WordDocx\ProjectExportController::class, 'exportWordViaApi'])->name('project.export.word');

});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        session(['locale' => $locale]);
    }
    return back();
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('setting', 'v_beta.settings.index')->name('setting');
});

require __DIR__.'/auth.php';

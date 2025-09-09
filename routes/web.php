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

// Project
Route::view('/project-list', 'v_beta.project-list')->name('project.list');
// Route::view('/project-create', 'v_beta.project-design')->name('project.create');
// Route::view('/project-edit/{projectId}', 'v_beta.project-design-edit')->name('project.edit');

Route::view('/project-dashboard/{projectId}/management', 'v_beta.project-dashboard')->name('project.dashboard');

// End Project

// Admin - IT

Route::view('/admin-it/project/list', 'v_beta.admin.project.index')->name('admin.it.project.list');

Route::view('/admin-it/type_of_project', 'v_beta.admin.type_of_project.index')->middleware(['auth'])->name('admin.it.type.of.project');

Route::view('/admin-it/type_of_project/{projectTypeId}/show', 'v_beta.admin.type_of_project.show')->middleware(['auth'])->name('admin.it.type.of.project.show');

// Route pour afficher le formulaire de création
Route::view('/admin-it/project-types/create', 'v_beta.admin.type_of_project.form')->name('admin.it.project.types.create');

// Route pour afficher le formulaire d'édition
Route::view('/admin-it/project-types/{projectTypeId}/edit', 'v_beta.admin.type_of_project.form')->name('admin.it.project.types.edit');

// Gestion Membres
Route::view('/admin-it/members/list', 'v_beta.admin.member.index')->name('admin.it.member.list');

// Gestion Categories
Route::view('/admin-it/categories/list', 'v_beta.admin.category.index')->name('admin.it.category.list');

// Gestion Corbeille
Route::view('/admin-it/trash/management', 'v_beta.admin.trash.index')->name('admin.it.trash.management');

// End Admin - IT




// Proposition de projet Start

// Route pour afficher le formulaire de création
Route::view('/creator-proposal/proposal-project/create', 'v_beta.proposal-project.form')->name('creator.proposal.project.create');

// Route pour afficher le formulaire d'édition
Route::view('/creator-proposal/proposal-project/{projectId}/edit', 'v_beta.proposal-project.form')->name('creator.proposal.project.edit');

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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

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

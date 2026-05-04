<?php
use Illuminate\Support\Facades\Route;

/**
 * ── ORGANIZATION ADMINISTRATION (ORG_ADMIN & AUTHORIZED USERS) ──
 * Routes pour la gestion quotidienne au sein d'une organisation.
 * Utilisable uniquement par les Admins d'Espace ou le Root.
 */
Route::middleware(['auth', 'account_type:org_admin,independent,system_admin'])->prefix('v1/admin')->name('admin.')->group(function () {
    // Projets & Configuration métier
    Route::view('/project/list', 'v1.admin.project.index')->name('project.list');
    Route::view('/type_of_project', 'v1.admin.type_of_project.index')->name('type.of.project');
    Route::view('/type_of_project/create', 'v1.admin.type_of_project.form')->name('project.types.create');
    Route::view('/type_of_project/{projectTypeId}/edit', 'v1.admin.type_of_project.form')->name('project.types.edit');
    Route::view('/type_of_project/{projectTypeId}/show', 'v1.admin.type_of_project.show')->name('project.types.show');
    
    // Gestion des ressources humaines et taxonomies
    Route::view('/members/list', 'v1.admin.member.index')->name('member.list');
    Route::view('/categories/list', 'v1.admin.category.index')->name('category.list');

    // Invitations
    Route::view('/invitations', 'v1.admin.invitation.index')->name('invitation.list');

    // Taux de change
    Route::get('/exchange-rates', \App\Livewire\V1\Admin\ExchangeRateManagementLivewire::class)->name('exchange-rates');

    // Import Excel
    Route::view('/import', 'v1.admin.import.index')->name('import');

    // Audit (org-scoped)
    Route::view('/audit', 'v1.admin.audit.index')->name('audit');

    // Permissions (org-scoped)
    Route::view('/permissions', 'v1.admin.permissions.index')->name('permissions');

    // Utilitaires
    Route::view('/trash/management', 'v1.admin.trash.index')->name('trash.management');
});

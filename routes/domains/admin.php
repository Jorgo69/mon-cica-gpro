<?php
use Illuminate\Support\Facades\Route;

/**
 * ── ORGANIZATION ADMINISTRATION (ORG_ADMIN & AUTHORIZED USERS) ──
 * Routes pour la gestion quotidienne au sein d'une organisation.
 * Utilisable uniquement par les Admins d'Espace ou le Root.
 */
Route::middleware(['auth', 'account_type:org_admin,system_admin'])->prefix('v_beta/admin')->name('admin.')->group(function () {
    // Projets & Configuration métier
    Route::view('/project/list', 'v_beta.admin.project.index')->name('project.list');
    Route::view('/type_of_project', 'v_beta.admin.type_of_project.index')->name('type.of.project');
    
    // Gestion des ressources humaines et taxonomies
    Route::view('/members/list', 'v_beta.admin.member.index')->name('member.list');
    Route::view('/categories/list', 'v_beta.admin.category.index')->name('category.list');

    // Utilitaires
    Route::view('/trash/management', 'v_beta.admin.trash.index')->name('trash.management');
});

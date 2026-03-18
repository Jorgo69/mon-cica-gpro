<?php

use Illuminate\Support\Facades\Route;

/**
 * ── SYSTEM MANAGEMENT (SUPER ADMIN / IT_ADMIN ONLY) ──
 * Routes globales pour la configuration du système, rôles, permissions et organisations.
 */
Route::middleware(['auth'])->prefix('v_beta/system')->name('system.')->group(function () {
    // Rôles & Permissions
    Route::view('/roles', 'v_beta.system.roles.index')->name('roles');
    Route::view('/permissions', 'v_beta.system.permissions.index')->name('permissions');
    
    // Organisations
    Route::view('/organizations', 'v_beta.system.organizations.index')->name('organizations');

    // Audit Logs (Global)
    Route::view('/audit/logs', 'v_beta.admin.audit.index')->name('audit.logs');
});

/**
 * ── ORGANIZATION ADMINISTRATION (ORG_ADMIN & AUTHORIZED USERS) ──
 * Routes pour la gestion quotidienne au sein d'une organisation.
 */
Route::middleware(['auth'])->prefix('v_beta/admin')->name('admin.')->group(function () {
    // Projets & Configuration métier
    Route::view('/project/list', 'v_beta.admin.project.index')->name('project.list');
    Route::view('/type_of_project', 'v_beta.admin.type_of_project.index')->name('type.of.project');
    
    // Gestion des ressources humaines et taxonomies
    Route::view('/members/list', 'v_beta.admin.member.index')->name('member.list');
    Route::view('/categories/list', 'v_beta.admin.category.index')->name('category.list');

    // Utilitaires
    Route::view('/trash/management', 'v_beta.admin.trash.index')->name('trash.management');
});

<?php

use Illuminate\Support\Facades\Route;

/**
 * ── ORGANIZATION ADMINISTRATION ──
 * Accessible par : org_admin (account_type) OU users avec permission manage-organization/manage-users.
 */
Route::middleware(['auth', 'account_type:org_admin,system_admin'])
    ->prefix('v_beta/admin')
    ->name('admin.')
    ->group(function () {
        // Projets (liste admin)
        Route::view('/project/list', 'pages.admin.project.index')->name('project.list');

        // Types de projet (CRUD)
        Route::view('/type_of_project', 'pages.admin.project-type.index')->name('type.of.project');
        Route::view('/type_of_project/create', 'pages.admin.project-type.form')->name('project.types.create');
        Route::view('/type_of_project/{projectTypeId}/edit', 'pages.admin.project-type.form')->name('project.types.edit');
        Route::view('/type_of_project/{projectTypeId}/show', 'pages.admin.project-type.show')->name('project.types.show');

        // Membres
        Route::view('/members/list', 'pages.admin.member.index')->name('member.list');

        // Catégories
        Route::view('/categories/list', 'pages.admin.category.index')->name('category.list');

        // Corbeille
        Route::view('/trash/management', 'pages.admin.trash.index')->name('trash.management');
    });

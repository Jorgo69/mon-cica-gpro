<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('v_beta/admin-it')->name('admin.it.')->group(function () {
    // Projets globaux
    Route::view('/project/list', 'v_beta.admin.project.index')->name('project.list');

    // Types de projets
    Route::view('/type_of_project', 'v_beta.admin.type_of_project.index')->name('type.of.project');
    Route::view('/type_of_project/{projectTypeId}/show', 'v_beta.admin.type_of_project.show')->name('type.of.project.show');
    Route::view('/project-types/create', 'v_beta.admin.type_of_project.form')->name('project.types.create');
    Route::view('/project-types/{projectTypeId}/edit', 'v_beta.admin.type_of_project.form')->name('project.types.edit');

    // Membres & Catégories
    Route::view('/members/list', 'v_beta.admin.member.index')->name('member.list');
    Route::view('/categories/list', 'v_beta.admin.category.index')->name('category.list');

    // Corbeille
    Route::view('/trash/management', 'v_beta.admin.trash.index')->name('trash.management');
});

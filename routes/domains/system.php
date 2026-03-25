<?php

use Illuminate\Support\Facades\Route;

/**
 * ── SYSTEM MANAGEMENT (ROOT / SYSTEM_ADMIN ONLY) ──
 * Routes globales pour la configuration du système, rôles, permissions et organisations.
 * Protégé par le middleware account_type pour garantir l'isolation totale.
 */
Route::middleware(['auth', 'account_type:system_admin'])->prefix('v_beta/system')->name('system.')->group(function () {
    // Rôles & Permissions
    Route::view('/roles', 'pages.system.roles.index')->name('roles');
    Route::view('/permissions', 'pages.system.permissions.index')->name('permissions');
    
    // Organisations
    Route::view('/organizations', 'pages.system.organizations.index')->name('organizations');

    // Audit Logs (Global)
    Route::view('/audit/logs', 'pages.admin.audit.index')->name('audit.logs');
});

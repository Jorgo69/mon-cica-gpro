<?php

use App\Http\Controllers\System\OrgSwitchController;
use Illuminate\Support\Facades\Route;

/**
 * ── SYSTEM MANAGEMENT (ROOT / SYSTEM_ADMIN ONLY) ──
 * Routes globales pour la configuration du système, rôles, permissions et organisations.
 * Protégé par le middleware account_type pour garantir l'isolation totale.
 */
// System routes only available in SaaS mode
if (isSaas()) {
Route::middleware(['auth', 'account_type:system_admin'])->prefix('v1/system')->name('system.')->group(function () {
    // Dashboard ROOT
    Route::view('/dashboard', 'v1.system.dashboard.index')->name('dashboard');

    // Organisations
    Route::view('/organizations', 'v1.system.organizations.index')->name('organizations');

    // Utilisateurs globaux
    Route::view('/users', 'v1.system.users.index')->name('users');

    // Emails / Suppression list
    Route::view('/emails', 'v1.system.emails.index')->name('emails');

    // Rôles & Permissions
    Route::view('/roles', 'v1.system.roles.index')->name('roles');
    Route::view('/permissions', 'v1.system.permissions.index')->name('permissions');

    // Audit Logs (Global)
    Route::view('/audit/logs', 'v1.admin.audit.index')->name('audit.logs');

    // Plans management
    Route::view('/plans', 'v1.system.plans.index')->name('plans');

    // AI Config (Global)
    Route::view('/ai-config', 'v1.system.ai-config.index')->name('ai-config');

    // Plugins / Marketplace
    Route::view('/plugins', 'v1.system.plugins.index')->name('plugins');

    // Org Switch (Entrer/Quitter une organisation) — GET pour eviter les problemes CSRF/Livewire
    Route::get('/org/{organizationId}/enter', [OrgSwitchController::class, 'enter'])->name('org.enter');
    Route::get('/org/leave', [OrgSwitchController::class, 'leave'])->name('org.leave');
});
} // end isSaas()

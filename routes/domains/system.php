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
Route::middleware(['auth', 'account_type:system_admin'])->prefix('v_beta/system')->name('system.')->group(function () {
    // Dashboard ROOT
    Route::view('/dashboard', 'v_beta.system.dashboard.index')->name('dashboard');

    // Organisations
    Route::view('/organizations', 'v_beta.system.organizations.index')->name('organizations');

    // Utilisateurs globaux
    Route::view('/users', 'v_beta.system.users.index')->name('users');

    // Emails / Suppression list
    Route::view('/emails', 'v_beta.system.emails.index')->name('emails');

    // Rôles & Permissions
    Route::view('/roles', 'v_beta.system.roles.index')->name('roles');
    Route::view('/permissions', 'v_beta.system.permissions.index')->name('permissions');

    // Audit Logs (Global)
    Route::view('/audit/logs', 'v_beta.admin.audit.index')->name('audit.logs');

    // Plans management
    Route::view('/plans', 'v_beta.system.plans.index')->name('plans');

    // AI Config (Global)
    Route::view('/ai-config', 'v_beta.system.ai-config.index')->name('ai-config');

    // Org Switch (Entrer/Quitter une organisation) — GET pour eviter les problemes CSRF/Livewire
    Route::get('/org/{organizationId}/enter', [OrgSwitchController::class, 'enter'])->name('org.enter');
    Route::get('/org/leave', [OrgSwitchController::class, 'leave'])->name('org.leave');
});
} // end isSaas()

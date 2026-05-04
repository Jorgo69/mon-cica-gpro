<?php

use App\Http\Controllers\Api\V1\ActivityApiController;
use App\Http\Controllers\Api\V1\GeneralApiController;
use App\Http\Controllers\Api\V1\ProjectApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
|
| Authenticated via Sanctum Bearer tokens.
| Rate limited to 60 requests per minute.
|
*/

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->name('api.v1.')->group(function () {

    // Me / Auth
    Route::get('/me', [GeneralApiController::class, 'me'])->name('me');

    // Projects
    Route::get('/projects', [ProjectApiController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectApiController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectApiController::class, 'show'])->name('projects.show');
    Route::put('/projects/{id}', [ProjectApiController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectApiController::class, 'destroy'])->name('projects.destroy');

    // Project activities
    Route::get('/projects/{id}/activities', [ProjectApiController::class, 'activities'])->name('projects.activities');
    Route::post('/projects/{id}/activities', [ProjectApiController::class, 'storeActivity'])->name('projects.activities.store');

    // Activities
    Route::get('/activities', [ActivityApiController::class, 'index'])->name('activities.index');
    Route::get('/activities/{id}', [ActivityApiController::class, 'show'])->name('activities.show');
    Route::put('/activities/{id}', [ActivityApiController::class, 'update'])->name('activities.update');

    // Members
    Route::get('/members', [GeneralApiController::class, 'members'])->name('members.index');

    // Stats
    Route::get('/stats', [GeneralApiController::class, 'stats'])->name('stats');

    // Notifications
    Route::get('/notifications', [GeneralApiController::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [GeneralApiController::class, 'markNotificationRead'])->name('notifications.read');

    // Audit logs
    Route::get('/audit-logs', [GeneralApiController::class, 'auditLogs'])->name('audit.index');
});

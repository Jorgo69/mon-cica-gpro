<?php

use App\Http\Controllers\InvitationController;
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

// Public shared project dashboard (no auth required)
Route::get('/shared/project/{token}', [\App\Http\Controllers\SharedProjectController::class, 'show'])
    ->name('shared.project');


Route::get('dashboard', \App\Livewire\VBeta\DashboardLivewire::class)
    ->middleware(['auth'])
    ->name('dashboard');

// --- Domain Driven Routes ---

require __DIR__.'/domains/system.php';
require __DIR__.'/domains/admin.php';
require __DIR__.'/domains/project.php';
require __DIR__.'/domains/resource.php';

// ----------------------------

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        session(['locale' => $locale]);
        if (auth()->check()) {
            \App\Services\UserMeta::set('locale', $locale);
        }
    }
    return back();
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('setting', 'v_beta.settings.index')->name('setting');

    // Mini API pour persister les preferences (theme toggle navbar, etc.)
    Route::post('/api/user-meta', function (\Illuminate\Http\Request $request) {
        $key = $request->input('key');
        $value = $request->input('value');
        if (in_array($key, ['theme', 'density', 'locale', 'avatar'])) {
            \App\Services\UserMeta::set($key, $value);
            return response()->json(['ok' => true]);
        }
        return response()->json(['error' => 'invalid key'], 422);
    })->name('user-meta.update');

    // FCM push tokens
    Route::post('/api/fcm-tokens', [\App\Http\Controllers\Api\FcmTokenController::class, 'store'])->name('fcm-token.store');
    Route::delete('/api/fcm-tokens/{token}', [\App\Http\Controllers\Api\FcmTokenController::class, 'destroy'])->name('fcm-token.destroy');
});

// Invitation (route publique, pas besoin d'auth)
Route::get('/invitation/{token}', InvitationController::class)->name('invitation.accept');

// Email unsubscribe/resubscribe (routes publiques, signees par token)
Route::get('/email/unsubscribe/{token}', [\App\Http\Controllers\EmailUnsubscribeController::class, 'unsubscribe'])->name('email.unsubscribe');
Route::get('/email/resubscribe/{token}', [\App\Http\Controllers\EmailUnsubscribeController::class, 'resubscribe'])->name('email.resubscribe');

require __DIR__.'/auth.php';

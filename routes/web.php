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
    }
    return back();
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('setting', 'v_beta.settings.index')->name('setting');
});

// Invitation (route publique, pas besoin d'auth)
Route::get('/invitation/{token}', InvitationController::class)->name('invitation.accept');

// Email unsubscribe/resubscribe (routes publiques, signees par token)
Route::get('/email/unsubscribe/{token}', [\App\Http\Controllers\EmailUnsubscribeController::class, 'unsubscribe'])->name('email.unsubscribe');
Route::get('/email/resubscribe/{token}', [\App\Http\Controllers\EmailUnsubscribeController::class, 'resubscribe'])->name('email.resubscribe');

require __DIR__.'/auth.php';

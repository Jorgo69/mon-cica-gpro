<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('v_beta')->group(function () {
    // Ressources
    Route::view('/resource/management', 'v_beta.resource.index')->name('resource.index');

    // Activities
    Route::prefix('activity')->name('activity.')->group(function() {
        Route::view('/list', 'v_beta.activity.index')->name('index');
        Route::view('/{activity}/management', 'v_beta.activity.management')->name('management');
    });
});

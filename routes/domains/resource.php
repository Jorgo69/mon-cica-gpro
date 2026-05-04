<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('v1')->group(function () {
    // Ressources
    Route::view('/resource/management', 'pages.resource.index')->name('resource.index');

    // Activities
    Route::prefix('activity')->name('activity.')->group(function() {
        Route::view('/list', 'pages.activity.index')->name('index');
        Route::view('/{activity}/management', 'pages.activity.management')->name('management');
    });
});

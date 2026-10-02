<?php

use App\Http\Controllers\LandingController;

/**
 * Home routes.
 */
Route::get('/', [LandingController::class, 'index'])->name('crm.home');

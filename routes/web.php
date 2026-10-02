<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/**
 * Public Landing Page Routes
 */
Route::get('/', [LandingController::class, 'index'])->name('crm.landing');
Route::post('/landing/lead', [LandingController::class, 'storeLead'])->name('crm.landing.lead');

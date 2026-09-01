<?php

use Illuminate\Support\Facades\Route;
use Crm\Admin\Http\Controllers\Controller;

/**
 * Home routes.
 */
Route::get('/', [Controller::class, 'redirectToLogin'])->name('crm.home');

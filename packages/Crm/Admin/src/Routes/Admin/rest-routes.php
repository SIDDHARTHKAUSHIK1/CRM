<?php

use Illuminate\Support\Facades\Route;
use Crm\Admin\Http\Controllers\DashboardController;
use Crm\Admin\Http\Controllers\DataGrid\SavedFilterController;
use Crm\Admin\Http\Controllers\DataGridController;
use Crm\Admin\Http\Controllers\TinyMCEController;
use Crm\Admin\Http\Controllers\User\AccountController;

/**
 * Dashboard routes.
 */
Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
    Route::get('', 'index')->name('admin.dashboard.index');

    Route::get('stats', 'stats')->name('admin.dashboard.stats');
});

/**
 * DataGrid routes.
 */
Route::prefix('datagrid')->group(function () {
    /**
     * Saved filter routes.
     */
    Route::controller(SavedFilterController::class)->prefix('datagrid/saved-filters')->group(function () {
        Route::post('', 'store')->name('admin.datagrid.saved_filters.store');

        Route::get('', 'get')->name('admin.datagrid.saved_filters.index');

        Route::put('{id}', 'update')->name('admin.datagrid.saved_filters.update');

        Route::delete('{id}', 'destroy')->name('admin.datagrid.saved_filters.destroy');
    });

    /**
     * Lookup routes.
     */
    Route::get('datagrid/look-up', [DataGridController::class, 'lookUp'])->name('admin.datagrid.look_up');
});

/**
 * Tinymce file upload handler.
 */
Route::post('tinymce/upload', [TinyMCEController::class, 'upload'])->name('admin.tinymce.upload');

/**
 * User profile routes.
 */
Route::controller(AccountController::class)->prefix('account')->group(function () {
    Route::get('', 'edit')->name('admin.user.account.edit');

    Route::put('update', 'update')->name('admin.user.account.update');
});

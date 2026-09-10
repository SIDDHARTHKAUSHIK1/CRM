<?php

use Illuminate\Support\Facades\Route;
use Crm\Admin\Http\Controllers\Admin\EmployeeController;

Route::prefix('panel')->group(function () {
    Route::controller(EmployeeController::class)->prefix('employees')->group(function () {
        Route::get('', 'index')->name('admin.panel.employees.index');
        Route::get('create', 'create')->name('admin.panel.employees.create');
        Route::post('', 'store')->name('admin.panel.employees.store');
        Route::get('view/{id}', 'view')->name('admin.panel.employees.view');
        Route::get('edit/{id}', 'edit')->name('admin.panel.employees.edit');
        Route::put('edit/{id}', 'update')->name('admin.panel.employees.update');
        Route::post('password/{id}', 'updatePassword')->name('admin.panel.employees.update_password');
        Route::post('reveal-password/{id}', 'revealPassword')->middleware('throttle:10,1')->name('admin.panel.employees.reveal_password');
        Route::post('impersonate/{id}', 'impersonate')->name('admin.panel.employees.impersonate');
        Route::post('impersonate-stop', 'impersonateStop')->name('admin.panel.employees.impersonate_stop');
        Route::delete('{id}', 'destroy')->name('admin.panel.employees.delete');
    });
});

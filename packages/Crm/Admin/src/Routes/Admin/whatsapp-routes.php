<?php

use Illuminate\Support\Facades\Route;
use Crm\Admin\Http\Controllers\WhatsApp\CampaignController;

Route::controller(CampaignController::class)->prefix('whatsapp')->group(function () {
    Route::get('', 'index')->name('admin.whatsapp.index');

    Route::get('create', 'create')->name('admin.whatsapp.create');
    Route::post('create', 'store')->name('admin.whatsapp.store');

    Route::get('preview/{id}', 'preview')->name('admin.whatsapp.preview');
    Route::get('rejected/{id}', 'downloadRejected')->name('admin.whatsapp.download_rejected');

    Route::post('start/{id}', 'startCampaign')->name('admin.whatsapp.start');
    Route::post('update/{id}', 'update')->name('admin.whatsapp.update');

    Route::get('show/{id}', 'show')->name('admin.whatsapp.show');
    Route::get('status/{id}', 'status')->name('admin.whatsapp.status');

    Route::post('pause/{id}', 'pause')->name('admin.whatsapp.pause');
    Route::post('resume/{id}', 'resume')->name('admin.whatsapp.resume');
    Route::post('cancel/{id}', 'cancel')->name('admin.whatsapp.cancel');
    Route::post('retry/{id}', 'retryFailed')->name('admin.whatsapp.retry');

    Route::delete('{id}', 'destroy')->name('admin.whatsapp.delete');

    Route::get('gateway', 'gateway')->name('admin.whatsapp.gateway');
    Route::get('gateway/status', 'gatewayStatus')->name('admin.whatsapp.gateway.status');
    Route::get('gateway/qr', 'gatewayQr')->name('admin.whatsapp.gateway.qr');
    Route::post('gateway/logout', 'gatewayLogout')->name('admin.whatsapp.gateway.logout');

    Route::get('dnc', 'dnc')->name('admin.whatsapp.dnc');
    Route::post('dnc', 'dncStore')->name('admin.whatsapp.dnc.store');
    Route::delete('dnc/{id}', 'dncDestroy')->name('admin.whatsapp.dnc.delete');
});

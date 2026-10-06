<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TrackingController::class, 'index'])->name('home');
Route::get('/track', [TrackingController::class, 'track'])->name('track');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [ShipmentController::class, 'index'])->name('dashboard');
        Route::get('/shipments/create', [ShipmentController::class, 'create'])->name('shipments.create');
        Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');
        Route::get('/shipments/{shipment}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
        Route::put('/shipments/{shipment}', [ShipmentController::class, 'update'])->name('shipments.update');
        Route::delete('/shipments/{shipment}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');
        Route::post('/shipments/{shipment}/events', [ShipmentController::class, 'addEvent'])->name('shipments.events.store');
        Route::delete('/shipments/{shipment}/events/{event}', [ShipmentController::class, 'deleteEvent'])->name('shipments.events.destroy');
        Route::post('/shipments/{shipment}/charges', [ShipmentController::class, 'addCharge'])->name('shipments.charges.store');
        Route::put('/shipments/{shipment}/charges/{charge}', [ShipmentController::class, 'updateCharge'])->name('shipments.charges.update');
        Route::delete('/shipments/{shipment}/charges/{charge}', [ShipmentController::class, 'deleteCharge'])->name('shipments.charges.destroy');
        Route::post('/shipments/{shipment}/status', [ShipmentController::class, 'quickStatus'])->name('shipments.status');
        Route::post('/shipments/{shipment}/duplicate', [ShipmentController::class, 'duplicate'])->name('shipments.duplicate');

        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account');
    });
});

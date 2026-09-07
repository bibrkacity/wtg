<?php


use App\Http\Controllers\Api\ImportController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\PropertyController;
use Illuminate\Support\Facades\Route;

Route::post('/imports', [ImportController::class, 'store'])->name('imports');

Route::controller(ImportController::class)->group(function () {
    Route::post('/imports', 'store')->name('imports.store');
    Route::get('/imports/{import}', 'show')->name('imports.show');
});

Route::controller(PropertyController::class)->group(function () {
    Route::get('/properties', 'index')->name('properties.index');
});

Route::controller(OfferController::class)->group(function () {
    Route::post('/offers/{offer}/reservations', 'reservation')->name('offers.reservation');
});

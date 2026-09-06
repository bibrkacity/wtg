<?php

use App\Http\Controllers\Api\ImportController;
use Illuminate\Support\Facades\Route;

Route::post('/imports', [ImportController::class, 'store'])->name('imports');

Route::controller(ImportController::class)->group(function () {
    Route::post('/imports', 'store')->name('imports.store');
    Route::get('/imports/{import}', 'show')->name('imports.show');
});

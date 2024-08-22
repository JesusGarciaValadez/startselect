<?php

use App\Http\Controllers\ConversionController;
use App\Http\Controllers\OperationController;
use Illuminate\Support\Facades\Route;

Route::controller(OperationController::class)->group(function () {
    Route::get('/operations', 'index')->name('operations');
    Route::get('/operation/create', 'create')->name('operation.create');
    Route::post('/operation/store', 'store')->name('operation.store');
    Route::delete('/operation/{operation}', 'destroy')->name('operation.destroy');
});

Route::controller(ConversionController::class)->group(function () {
    Route::get('/conversions', 'index')->name('conversions');
    Route::get('/conversion/create', 'create')->name('conversion.create');
    Route::post('/conversion/store', 'store')->name('conversion.store');
    Route::delete('/conversion/{conversion}', 'destroy')->name('conversion.destroy');
});

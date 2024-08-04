<?php

use App\Http\Controllers\MpesaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'mpesa'], function () {
    // Define your routes here
    Route::post('/stkpush', [MpesaController::class, 'stkPush'])->name('mpesa.stkpush');
    // Add other routes as needed
});

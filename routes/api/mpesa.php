<?php

use App\Http\Controllers\MpesaController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'mpesa'], function () {
    // Define your routes here
    Route::post('/stkpush', [MpesaController::class, 'stkPush'])->name('mpesa.stkpush');
    Route::post("/confirmation", [MpesaController::class, 'confirmation'])->name('mpesa.confirmation');
    // Add other routes as needed
});

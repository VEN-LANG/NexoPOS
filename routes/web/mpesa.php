<?php

use App\Http\Controllers\MpesaTransactionsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'mpesa'], function () {
    Route::post("/callback", [MpesaTransactionsController::class, 'handleCallback']);
})->withoutMiddleware(['web']);

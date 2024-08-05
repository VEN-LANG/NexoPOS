<?php

namespace App\Http\Controllers;

use App\Models\MpesaTransactions;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaTransactionsController extends Controller
{
    /*
    * This function is used to store the mpesa transactions in the database
     * Handle Callback from Safaricom
    */

    public function handleCallback(Request $request): void
    {
        // Handle Mpesa Callback After customer has paid
       (new MpesaService())->handleCallBack($request);

    }
}

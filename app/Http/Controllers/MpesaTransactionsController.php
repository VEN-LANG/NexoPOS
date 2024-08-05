<?php

namespace App\Http\Controllers;

use App\Models\MpesaTransactions;
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
        $data = $request->getContent();
        $decodedData = json_decode($data, true);
        Log::info("Callback data: ", $decodedData);
        // Find Payment by transaction ID

        try {
            $payment = MpesaTransactions::query()
                ->where('MerchantRequestID', $decodedData['Body']['stkCallback']['MerchantRequestID'])
                ->where("CheckoutRequestID", $decodedData['Body']['stkCallback']['CheckoutRequestID'])
                ->first();
            Log::info("Payment: ", [
                $payment
            ]);
            $paymentData = [
                'transaction_code' => (string)$decodedData['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'],
                'phone_number' => (string)$decodedData['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value'],
                'transaction_date' => \DateTime::createFromFormat('YmdHis', (string)$decodedData['Body']['stkCallback']['CallbackMetadata']['Item'][2]['Value']),
                'transaction_amount' => (string)$decodedData['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'],
            ];
            Log::info("Payment Data: ", $paymentData);
            $payment?->update($paymentData);
        } catch (\Exception $e) {
            Log::error("Error: ", [$e->getMessage()]);
        }

    }
}

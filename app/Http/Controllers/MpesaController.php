<?php

namespace App\Http\Controllers;

use App\Http\Requests\StkPushRequest;
use App\Models\MpesaTransactions;
use App\Services\MpesaService;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    /**
     * @param StkPushRequest $request
     * @return string[]
     */
    public function stkPush(StkPushRequest $request)
    {
        $mpesaService = new MpesaService();
        $data = [
            'amount' => $request->amount,
            'phoneNumber' => $request->phoneNumber,
            'transactionType' => 'CustomerPayBillOnline',
            'accountReference' => "Mpesa payment for Order",
            'transactionDesc' => 'Order Payment',
        ];
        $response = $mpesaService->sendStkPush($data);
        $transaction = MpesaTransactions::query()
            ->create([
                'MerchantRequestID' => $response['response']['MerchantRequestID'],
                'CheckoutRequestID' => $response['response']['CheckoutRequestID'],
                'phone_number' => $request->phoneNumber,
                ''
            ]);
        if ($transaction)
        {
            return [
                'success' => true,
                'transactionDateTime' => $transaction->created_at,
                'transaction' => $transaction
            ];
        }
        return [
            'status' => 'error',
            'message' => 'Failed to create transaction'
        ];
    }

    /**
     * @param Request $request
     * @return string[]
     */
    public function syncPayment(Request $request){

        return [
            'status' => 'success',
        ];
    }

    public function confirmation(Request $request)
    {
        // Check for mpesa Transaction with same number
        $transaction = MpesaTransactions::query()
            ->where("phone_number", $request->phoneNumber)
            ->whereDoesntHave("order")
            ->whereBetween("transaction_date", [now()->startOfDay(), now()->endOfDay()])
            ->first();
        if ($transaction)
        {
            // return Mpesa Transaction
            return [
                'status' => 'success',
                'transaction' => $transaction
            ];
        }
        return [
            'status' => 'error',
            'message' => 'No transaction found'
        ];
    }
}

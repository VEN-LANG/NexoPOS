<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaTransactions extends Model
{
    use HasFactory;
    public string $CALL_BACK_URL = "https://api.safaricom.co.ke/mpesa/stk";
    protected $table = 'mpesa_transactions';

    protected $fillable = [
        'MerchantRequestID',
        'CheckoutRequestID',
        'transaction_code',
        'phone_number',
        'transaction_date',
        'transaction_amount',
        "order_id"
    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }


}

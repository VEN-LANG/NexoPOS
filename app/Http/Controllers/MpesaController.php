<?php

namespace App\Http\Controllers;

use App\Http\Requests\StkPushRequest;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    /**
     * @param StkPushRequest $request
     * @return string[]
     */
    public function stkPush(StkPushRequest $request){
        return [
            'status' => 'success',
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

    public function stkResponse()
    {
        
    }
}

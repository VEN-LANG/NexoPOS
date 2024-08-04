<?php

namespace App\Http\Controllers;

use App\Http\Requests\StkPushRequest;
use Illuminate\Http\Request;

class MpesaController extends Controller
{
    //
    public function stkPush(StkPushRequest $request){
        return [
            'status' => 'success',
        ];
    }
}

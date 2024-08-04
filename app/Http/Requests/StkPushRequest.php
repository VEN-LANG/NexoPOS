<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StkPushRequest extends FormRequest
{

    public function authorize(){

        return true;
    }

    public function rules(){
        return [
          'amount' => 'required|numeric|min:1',
          'phoneNumber' => 'required|regex:/^\d{10,15}$/',
        ];
    }
}
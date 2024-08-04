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

    public function messages()
    {
        return [
            'phoneNumber.required' => __('The phone number field is required.'),
            'phoneNumber.regex' => __('The phone number must be between 10 and 15 digits.'),
            'amount.required' => __('The amount field is required.'),
            'amount.numeric' => __('The amount must be a number.'),
            'amount.min' => __('The amount must be at least 1.'),
        ];
    }
}
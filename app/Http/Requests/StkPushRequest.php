<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StkPushRequest extends FormRequest
{

    public function authorize(){

        return true;
    }

    /**
     * Clean phone number on request
     * @return void
     */
    protected function prepareForValidation()
    {
        $phoneNumber = $this->input('phoneNumber');

        // Remove the '+' if it exists
        $phoneNumber = ltrim($phoneNumber, '+');

        // Replace '07' or '01' at the start with '2547' or '2541'
        if (preg_match('/^07/', $phoneNumber)) {
            $phoneNumber = preg_replace('/^07/', '2547', $phoneNumber);
        } elseif (preg_match('/^01/', $phoneNumber)) {
            $phoneNumber = preg_replace('/^01/', '2541', $phoneNumber);
        }

        // Update the input with the cleaned phone number
        $this->merge([
            'phoneNumber' => $phoneNumber,
        ]);
    }
    public function rules()
    {
        return [
            'amount' => 'required','numeric','min:1',
            'phoneNumber' => ['required','min:10', 'regex:/^(?:\+?2547\d{8}|\+?2541\d{8}|07\d{8}|01\d{8}|2547\d{8}|2541\d{8})$/'],
        ];
    }

    public function messages()
    {
        return [
            'phoneNumber.required' => __('The phone number field is required.'),
            'phoneNumber.regex' => __('Invalid phone number format or less digits.'),
            'amount.required' => __('The amount field is required.'),
            'amount.numeric' => __('The amount must be a number.'),
            'amount.min' => __('The amount must be at least 1.'),
            'phoneNumber.min' => __('The phone number must be at least 10 characters.'),
        ];
    }
}
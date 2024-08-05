<?php

namespace App\Services;

use Illuminate\Http\Request;
use Kemboielvis\MpesaSdkPhp\Mpesa;

class MpesaService
{
    private string $callBackUrl;
    private  Mpesa $mpesa;
    private string $passKey;
    private string $businessCode;
    public function __construct()
    {
        $this->mpesa = new Mpesa();
        $credentials = [
            'consumer_key' => ns()->option->get('ns_accounting_mpesa_api_key'),
            'consumer_secret' => ns()->option->get('ns_accounting_mpesa_api_secret'),
        ];
        $this->callBackUrl = ns()->option->get('ns_accounting_mpesa_callback_url');
        $this->passKey = ns()->option->get('ns_accounting_mpesa_pass_key');
        $this->businessCode = ns()->option->get('ns_accounting_mpesa_business_code');

        $this->mpesa = $this->mpesa->setCredentials($credentials['consumer_key'], $credentials['consumer_secret']);
    }
    /**
     * @param array $data {
     * @var string $amount
     * @var string $phoneNumber
     * @var string $transactionType
     * @var string $accountReference
     * @var string $transactionDesc
     * }
     */
    public function sendStkPush(array $data): array
    {
        $stk = $this->mpesa->stk()
            ->businessCode($this->businessCode)
            ->amount(intval($data['amount']))
            ->phoneNumber($data['phoneNumber'])
            ->callBackUrl($this->callBackUrl)
            ->transactionType("CustomerPayBillOnline")
            ->accountReference($data['accountReference'])
            ->transactionDesc($data['transactionDesc'])
            ->passKey($this->passKey);
        // Get response in and store it after sending a push
        $push = $stk->push();
        $response = $push->response();
        // Query STK Push and check its status
        $transactionQuery = $push->query();
        \Log::info("Transaction Query: " . json_encode($transactionQuery));
        return [
            'response' => $response,
            'transactionQuery' => $transactionQuery
        ];
    }

    public function handleCallBack(Request $request)
    {
        $response = json_decode($request->getContent(), true);
        \Log::log('info', $response);

    }

    public function registerUrls()
    {
        $registerUrl = $this->mpesa->customerToBusiness()
            ->responseType("Completed")
            ->validationUrl("https://mydomain.com/confirmation")
            ->confirmationUrl("https://mydomain.com/confirmation")
            ->businessCode("600984")->registerUrl();
        // Get the response
        $response = $registerUrl->response();
    }
// (ns()->option->get( 'ns_accounting_mpesaallowed_accounts', 'no' ) === 'yes' ? true : false)
}
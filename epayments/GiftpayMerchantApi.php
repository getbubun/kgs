<?php

class GiftpayMerchantApi
{
    private $apikey = '';
    private $apisecret = '';

    public $userid = '';
    public $customid = '';
    public $fixedamount = 0;
    public $callbackurl = '';

    public $success = false;
    public $responseData = [];
    public $errorMessage = '';

    private $baseUrl = 'https://giftpay.io/merchant-api/';

    public function __construct($_apikey, $_apisecret)
    {
        $this->apikey = $_apikey;
        $this->apisecret = $_apisecret;
    }
    public function getOrderStatus($orderid)
    {
        $this->success = false;
        $this->responseData = [];
        $params = [
            "orderid" => $orderid
        ];
        $responseData = $this->sendRequest("getorder", $params);
        if (isset($responseData['error'])) {
            $this->errorMessage = $responseData['error'];
            return;
        }
        if (!isset($responseData['orderid'])) {
            $this->errorMessage = "Invalid response";
            return;
        }
        $this->responseData = $responseData;
        $this->success = true;
    }


    private function sendRequest($cmd, $params = [])
    {
        try {

            $endpoint = $this->baseUrl. $cmd;

            // add time to avoid caching issues
            $endpoint .= "?time=".time();

            // set apikey if not set
            if (!isset($params["apikey"])) {
                $params["apikey"] = $this->apikey;
            }

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            $header[] = 'Content-Type: application/json';
            $header[] = 'API-SECRET: ' . $this->apisecret;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $responseJson = curl_exec($ch);
            //echo $responseJson;
            curl_close($ch);
            if (!$responseJson || $responseJson == null || $responseJson == "") {
                return ["error" => "Empty response"];
            }
            $response = json_decode($responseJson, true);
            if($response == null){
                return ["error" => "Invalid response"];
            }
            return $response;
        } catch (Exception $ex) {
            return ["error"=> $ex->getMessage()];
        }
    }

}
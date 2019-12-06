<?php

ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);


require_once __DIR__ . '/GiftpayMerchantApi.php';

$sandbox = true;

$apikey = "5f42fd5b0701345502e8b15d0ca77b834cab6c022abea4fdf8877fc37df77d46";
$apisecret = "c51c81f988344d668b8b6d77394f738a24247b8a81ab91c86950de8e1ca5ba5b";
if ($sandbox) {
    $apikey = "6b06050fcc2df77f5a2566842107423a0aa1a2f09e6cb7654e0055cc0e374c01";
    $apisecret = "d631ff8c8a27097dd9ca871f862022e816e8a9aef66b0ce9f28c38f3bf21075e";
}
$api = new GiftpayMerchantApi($apikey, $apisecret);

$orderid = "b85a14d560e7804c09c3261cd143f8f2b4a96fef59d81eb3f5e8cd96ffacde2a"; // returned on client side or on IPN page, you can get it on your merchant panel also

$api->getOrderStatus($orderid);
$responseData = $api->responseData;
if($api->success){
    /*
{
    "orderid": "order id"
    "userid": "userid that you have sent",
    "amount": "amount that requested to pay",
    "uniqueid": "unique identification that you have sent or empty if not applied",
    "description": "description that you have sent or empty if not applied",
    "txnid": "transaction id, better readable for users",
    "cardtype": "card type ex: walmart, applestore, target, amazon",
    "cardlastfour": "last four digits/symbols of the card number",
    "cardvalue": "total card value submitted",
    "customerfee": "fee deducted from the card value for card types with lower market rates or complex selling methods if applied",
    "paidamount": "paid amount after deducting customer fee if applied",
    "fixedfee": "Fixed Fee that you decided to pay instead of the Customer if applied",
    "merchantfee": "paid amount after deducting customer fee if applied",
    "amountpaidtoyou": "paid to you after fees deducted",
    "status": "new|good|completed|cancelled"
};
    */

    echo "paidamount: $" . $responseData["paidamount"];
    echo "<br>amountpaidtoyou: $" . $responseData["amountpaidtoyou"];
    echo "<br>userid: " . $responseData["userid"];
    echo "<br>status: " . $responseData["status"];
}
else{
    // handle the error
    echo "ERROR: " . $api->errorMessage;
}
?>
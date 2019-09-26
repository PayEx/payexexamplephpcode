<?php

require_once 'resources/Curl.php';
use \resources\Curl;

$request = new Curl();
$settingsdata = require_once 'resources/settings.php';

$hostUrl = $settingsdata['hosturi'];

$urls = [
    "hostUrls" => [$hostUrl[0]],
    "completeUrl" => "https://example.com/payment-completed",
    "cancelUrl" => "https://example.com/payment-canceled",
    "callbackUrl" => "https://payexexamplephpcode.000webhostapp.com/resources/script_callback.php",
    "termsOfServiceUrl" => "https://payexexamplephpcode.000webhostapp.com/termsandconditions.pdf",
];

$payeeInfo = [
    "payeeId" => $settingsdata['payeeId'],
    "payeeReference" => date("Ymdhis") . rand(100, 1000),
    "orderReference" => "order-100",
    "payeeName" => "Merchant1",
    "productCategory" => "A100",
];

$metadata = [
    'key1' => 'value1',
    'key2' => 'value2',
];

$creditCard = [
    "no3DSecure" => false,
    "no3DSecureForStoredCard" => false,
    "rejectCardNot3DSecureEnrolled" => false,
    "rejectCreditCards" => false,
    "rejectDebitCards" => false,
    "rejectConsumerCards" => false,
    "rejectCorporateCards" => false,
    "rejectAuthenticationStatusA" => false,
    "rejectAuthenticationStatusU" => false,
    "noCvc" => false,
];

$invoice = [
    "feeAmount" => 1000,
    "invoiceType" => "PayExFinancingSe",
];

$swish = [
    "enableEcomOnly" => false,
];

$items = [ ['creditCard' => $creditCard], ['invoice' => $invoice], ['swish' => $swish] ];

$paymentorder = [
    'operation' => 'Purchase',
    'intent' => "Authorization",
    'currency' => "SEK",
    'amount' => 25000,
    'vatAmount' => 0,
    'description' => "Test Purchase",
    'userAgent' => "Mozilla/5.0",
    'language' => "nb-NO",
    'generatePaymentToken' => "false",
    'language' => "en-US",
    'disablePaymentMenu' => "false",
    'urls' => $urls,
    'payeeInfo' => $payeeInfo,
    //'metadata' => $metadata,
    'items' => $items,
];

$payload = [
    'paymentorder' => $paymentorder,
];

try {
    $response = $request->curlRequest(
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/paymentorders",
        json_encode($payload)
    );

    if ($response['statusCode'] == 201) {
        $operationsArray = $response['response']->{'operations'};
        $index = array_search('view-paymentorder', array_column($operationsArray, 'rel'));
        
        if (isset($index)) {
            $href = $operationsArray[$index]->{'href'};
            include 'templates/paymentorder.php';
            exit;
        }
    }
} catch (Exception $e) {
    // Exception handling
}

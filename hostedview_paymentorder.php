<?php

require_once 'resources/Curl.php';
use \resources\Curl;

$request = new Curl();
$settingsData = require_once 'resources/settings.php';

$hostUrl = $settingsData['hosturi'];

$urls = [
    "hostUrls" => [$hostUrl],
    "completeUrl" => $hostUrl . "/payment-completed",
    "paymentUrl" => $hostUrl . "/hostedview_paymentorder_paymentUrl.php",
    "cancelUrl" => $hostUrl . "/payment-canceled",
    "callbackUrl" => $hostUrl . "/resources/script_callback.php",
    "termsOfServiceUrl" => "https://payexexamplephpcode.000webhostapp.com/termsandconditions.pdf",
];

$payeeInfo = [
    "payeeId" => $settingsData['payeeId'],
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
    "rejectCreditCards" => false,
    "rejectDebitCards" => false,
    "rejectConsumerCards" => false,
    "rejectCorporateCards" => false,
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
        $settingsData['AuthorizationBearer'],
        "POST",
        $settingsData['baseuri'] . "/psp/paymentorders",
        json_encode($payload)
    );

    if ($response['statusCode'] == 201) {
        $operationsArray = $response['response']->{'operations'};
        $index = array_search('view-paymentorder', array_column($operationsArray, 'rel'));
        
        if (isset($index)) {
            $cookie_value = $response['response']->{'paymentOrder'}->{'id'};
            setcookie('paymentid_paymentOrder', $cookie_value, time() + (86400 * 30), "/");

            $href = $operationsArray[$index]->{'href'};
            include 'templates/paymentorder.php';
            exit();
        }
    }
} catch (Exception $e) {
    // Exception handling
}

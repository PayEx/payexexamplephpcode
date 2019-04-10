<?php

include 'resources/Curl.php';
$request = new Curl();
$settingsdata = include 'resources/settings.php';

$urls = [
    "hostUrls" => ['https://example.com', 'https://example.net'],
    "completeUrl" => "https://example.com/payment-completed",
    "cancelUrl" => "https://example.com/payment-canceled",
    "callbackUrl" => "https://api.example.com/payment-callback",
    "termsOfServiceUrl" => "https://example.com/termsandconditoons.pdf",
    "logoUrl" => "https://example.com/logo.png",
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
        
        if ($index == true) {
            $href = $operationsArray[$index]->{'href'};
            include 'templates/paymentmenu.php';
            exit;
        }
    }
} catch (Exception $e) {
    // Exception handling
}
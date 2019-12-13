<?php

namespace resources;

require_once 'Curl.php';
use \resources\Curl;

$request = new Curl();
$settingsData = require_once 'settings.php';

// HTTP GET call(jQuery) from onConsumerIdentifiedEvent - check templates/checkout.php

$hostUrl = $settingsData['hosturi'];

if (isset($_GET["consumerProfileRef"]) == true) {
    $urls = [
    "hostUrls" => [$hostUrl],
    "paymentUrl" => $hostUrl . "/hostedview_paymentorder_paymentUrl.php",
    "completeUrl" => $hostUrl . "/payment-completed",
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

    $consumerProfileRef = filter_input(INPUT_GET, 'consumerProfileRef', FILTER_SANITIZE_STRING);

    $payer = ['consumerProfileRef' => $consumerProfileRef];

    $paymentorder = [
    'operation' => 'Purchase',
    'intent' => "Authorization",
    'currency' => "SEK",
    'amount' => 25000,
    'vatAmount' => 0,
    'description' => "Test Purchase",
    'userAgent' => "Mozilla/5.0",
    'language' => "en-US",
    'generatePaymentToken' => "false",
    'disablePaymentMenu' => "false",
    'urls' => $urls,
    'payeeInfo' => $payeeInfo,
    'payer' => $payer,
    //'metadata' => $metadata,
    'items' => $items,
    ];

    $payloadPaymentorder = [
    'paymentorder' => $paymentorder,
    ];

    $responsePaymentorder = $request->curlRequest(
        $settingsData['AuthorizationBearer'],
        "POST",
        $settingsData['baseuri'] . "/psp/paymentorders",
        json_encode($payloadPaymentorder)
);

    if ($responsePaymentorder['statusCode'] == 201) {
        $operationsArray = $responsePaymentorder['response']->{'operations'};
        $index = array_search('view-paymentorder', array_column($operationsArray, 'rel'));

        if ($index == true) {
            $cookie_value = $responsePaymentorder['response']->{'paymentOrder'}->{'id'};
            setcookie('paymentid_paymentOrder', $cookie_value, time() + (86400 * 30), "/");

            $href = $operationsArray[$index]->{'href'};
            echo '<p class="paymentorder-JS-sourceuri">' . $href . '</p>';
            exit();
        }
    }
}

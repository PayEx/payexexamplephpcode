<?php

include 'resources/Curl.php';
$request = new Curl();
$settingsdata = include 'resources/settings.php';

// please see the response for creditcard => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/core-payment-resources/card-payments/
$paymentid = '/psp/creditcard/payments/b4bd10f0-60d8-40a7-5695-08d69621b33';

try {
    $response = $request->curlRequest(
        $settingsdata['AuthorizationBearer'],
        "GET",
        $settingsdata['baseuri'] . $paymentid,
        '' // payload content not needed, but empty string must be present because of the method parameter
    );
    if ($response['statusCode'] == 200) {
        // do something with $response['response']
    }
} catch (Exception $e) {
    // Exception handling
}
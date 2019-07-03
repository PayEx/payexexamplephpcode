<?php

require_once 'resources/Curl.php';
$request = new \resources\Curl();
$settingsdata = require_once 'resources/settings.php';

// please see the response for creditcard => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/core-payment-resources/card-payments/
$paymentid = '/psp/creditcard/payments/690a3b64-e087-49ea-5884-08d6c7caa2c3';

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
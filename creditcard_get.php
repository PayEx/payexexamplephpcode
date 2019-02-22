<?php

include 'resources/payexapi.php';
$request = new payexapi();
$settingsdata = include 'resources/settings.php';

// please see the response for creditcard => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/core-payment-resources/card-payments/
$paymentid = '/psp/creditcard/payments/b4bd10f0-60d8-40a7-5695-08d69621b33';

try {
    $response = $request->payex_request(
        $settingsdata['AuthorizationBearer'],
        "GET",
        $settingsdata['baseuri'] . $paymentid,
        '' // payload content not needed, but empty string must be present because of the method parameter
    );
} catch (Exception $e) {
    // Exception handling
}

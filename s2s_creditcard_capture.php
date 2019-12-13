<?php

require_once 'resources/Curl.php';
use \resources\Curl;

$request = new Curl();
$settingsData = require_once 'resources/settings.php';

// please see the response for creditcard => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/core-payment-resources/card-payments/
$paymentid = '/psp/creditcard/payments/f9651198-6b17-454d-3741-08d6ffb6e386';

try {
    $responseGET = $request->curlRequest(
        $settingsData['AuthorizationBearer'],
        "GET",
        $settingsData['baseuri'] . $paymentid,
        '' // payload content not needed, but empty string must be present because of the method parameter
    );
} catch (Exception $e) {
    // Exception handling
}

try {
    if ($responseGET['statusCode'] == 200) {
        $state = $responseGET['response']->{'payment'}->{'state'};
        $operationsArray = $responseGET['response']->{'operations'};
        $index = array_search('create-capture', array_column($operationsArray, 'rel'));

        if (isset($index)) {
            $method = $operationsArray[$index]->{'method'};
            $href = $operationsArray[$index]->{'href'};

            $transaction = [
                "amount" => 2500,
                "vatAmount" => 0,
                "description" => "test capture",
                "payeeReference" => date("Ymdhis") . rand(100, 1000),
                "orderReference" => "order-100",
            ];

            $payload = [
                'transaction' => $transaction,
            ];

            $response = $request->curlRequest(
                $settingsData['AuthorizationBearer'],
                $method,
                $href,
                json_encode($payload)
            );

            if ($response['statusCode'] == 201) {
                // capture created
            } elseif ($response['statusCode'] == 400) {
                //check problems object in JSON $response['response']
            }
        } else {
            // state not ready
        }
    }
} catch (Exception $e) {
    // Exception handling
}

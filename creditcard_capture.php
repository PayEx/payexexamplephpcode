<?php

include 'resources/Curl.php';
$request = new Curl();
$settingsdata = include 'resources/settings.php';

// please see the response for creditcard => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/core-payment-resources/card-payments/
$paymentid = '/psp/creditcard/payments/6be073a6-d494-4277-ff9f-08d6c7cabe65';

try {
    $responseGET = $request->curlRequest(
        $settingsdata['AuthorizationBearer'],
        "GET",
        $settingsdata['baseuri'] . $paymentid,
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

        if ($state == 'Ready' && $index == true) {
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
                $settingsdata['AuthorizationBearer'],
                $method,
                $href,
                json_encode($payload)
            );

            if ($response['statusCode'] == 201) {
                // $state = $response['response']->{'capture'}->{'transaction'}->{'state'};
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

<?php

include 'resources/payexapi.php';
$request = new payexapi();
$settingsdata = include 'resources/settings.php';

// please see the response for creditcard => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/core-payment-resources/card-payments/
$paymentid = '/psp/creditcard/payments/a81a3c4d-b25a-4bc8-2c32-08d69c7da718';

try {
    $responseGET = $request->payex_request(
        $settingsdata['AuthorizationBearer'],
        "GET",
        $settingsdata['baseuri'] . $paymentid,
        '' // payload content not needed, but empty string must be present because of the method parameter
    );
} catch (Exception $e) {
    // Exception handling
}

try {
    if ($responseGET['statuscode'] == 200) {
        $state = $responseGET['response']->{'payment'}->{'state'};
        $operationsArray = $responseGET['response']->{'operations'};
        $rel = 'create-capture';
        //$rel = 'create-reversal';
        $index = array_search($rel, array_column($operationsArray, 'rel'));

        if ($state == 'Ready' && $index == true) {
            $method = $operationsArray[$index]->{'method'};
            $href = $operationsArray[$index]->{'href'};

            $transaction = array
                (
                "amount" => 2500,
                "vatAmount" => 0,
                "description" => "test capture",
                "payeeReference" => date("Ymdhis") . rand(100, 1000),
                "orderReference" => "order-100",
            );

            $payload = array
                (
                'transaction' => $transaction,
            );

            $response = $request->payex_request(
                $settingsdata['AuthorizationBearer'],
                $method,
                $href,
                json_encode($payload)
            );

            if ($response['statuscode'] == 201) {
                if ($response['response']->{'capture'}->{'transaction'}->{'state'} == 'Completed') {
                    // do something when capture is Completed
                }
                //if ($response['response']->{'reversal'}->{'transaction'}->{'state'} == 'Completed') {/*reversal completed*/}
            }
        } else {
            // state not ready
        }
    }
} catch (Exception $e) {
    // Exception handling
}

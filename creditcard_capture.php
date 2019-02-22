<?php

include 'resources/payexapi.php';
$request = new payexapi();
$settingsdata = include 'resources/settings.php';

// please see the response for creditcard => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/core-payment-resources/card-payments/
$paymentid = '/psp/creditcard/payments/81c56cfc-7170-4e03-4839-08d6970479c6';

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
    $state = $responseGET->{'payment'}->{'state'};
    $operationsArray = $responseGET->{'operations'};
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
            $method,
            $settingsdata['AuthorizationBearer'],
            $href,
            json_encode($payload)
        );

        if ($response->{'capture'}->{'transaction'}->{'state'} == 'Completed') { /*capture completed*/}
        //if ($response->{'reversal'}->{'transaction'}->{'state'} == 'Completed') {/*reversal completed*/}
    } else {
        // state not ready
    }

} catch (Exception $e) {
    // Exception handling
}

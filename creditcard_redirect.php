<!DOCTYPE html>
<html>

<head>
    <title>creditcard redirect</title>
    <script src="jquery.min.js"></script>
</head>

<body>
    <?php

include 'resources/payexapi.php';
$request = new payexapi();
$settingsdata = include 'resources/settings.php';

$urls = array
    (
    "hostUrls" => array('https://example.com', 'https://example.net'),
    "completeUrl" => "https://example.com/payment-completed",
    "cancelUrl" => "https://example.com/payment-canceled",
    "callbackUrl" => "https://api.example.com/payment-callback",
    "termsOfServiceUrl" => "https://example.com/termsandconditoons.pdf",
    "logoUrl" => "https://example.com/logo.png",
);

$payeeInfo = array
    (
    "payeeId" => $settingsdata['payeeId'],
    "payeeReference" => date("Ymdhis") . rand(100, 1000),
    "orderReference" => "order-100",
    "payeeName" => "Merchant1",
    "productCategory" => "A100",
);

$prices = array
    (
    "type" => "creditcard",
    "amount" => 2500,
    "vatAmount" => 0,
);

$metadata = array
    (
    'key1' => 'value1',
    'key2' => 'value2',
);

$creditCard = array
    (
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
);

$payment = array
    (
    'operation' => 'Purchase',
    'intent' => "Authorization",
    'currency' => "SEK",
    'prices' => array($prices),
    'description' => "Test Purchase",
    'userAgent' => "Mozilla/5.0",
    'language' => "nb-NO",
    'generatePaymentToken' => "false",
    'urls' => $urls,
    'payeeInfo' => $payeeInfo,
    //'metadata' => $metadata,
);

$payload = array
    (
    'payment' => $payment,
    'creditCard' => $creditCard,
);

try {

    $response = $request->payex_request(
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/creditcard/payments",
        json_encode($payload)
    );

    $operationsArray = $response->{'operations'};
    $index = array_search('redirect-authorization', array_column($operationsArray, 'rel'));

    if ($index == true) {
        $redirecturl = $operationsArray[$index]->{'href'};
        header("Location: " . $redirecturl);
        die();
    }

} catch (Exception $e) {
    // Exception handling
}

?>
</body>

</html>
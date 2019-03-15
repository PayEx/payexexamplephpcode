<!DOCTYPE html>
<html>

<head>
    <title>creditcard redirect</title>
    <script src="jquery.min.js"></script>
</head>

<body>
    <div id="creditcard"></div>
    <?php

require __DIR__ . '/vendor/autoload.php';

include 'resources/Curl.php';
$request = new Curl();
$settingsdata = include 'resources/settings.php';

// Twig extension
$loader = new \Twig\Loader\FilesystemLoader('templates/');
$twig = new \Twig\Environment($loader);

$urls = array(
    "hostUrls" => array('https://example.com', 'https://example.net'),
    "completeUrl" => "https://example.com/payment-completed",
    "cancelUrl" => "https://example.com/payment-canceled",
    "callbackUrl" => "https://api.example.com/payment-callback",
    "termsOfServiceUrl" => "https://example.com/termsandconditoons.pdf",
    "logoUrl" => "https://example.com/logo.png",
);

$payeeInfo = array(
    "payeeId" => $settingsdata['payeeId'],
    "payeeReference" => date("Ymdhis") . rand(100, 1000),
    "orderReference" => "order-100",
    "payeeName" => "Merchant1",
    "productCategory" => "A100",
);

$prices = array(
    "type" => "creditcard",
    "amount" => 2500,
    "vatAmount" => 0,
);

$metadata = array(
    'key1' => 'value1',
    'key2' => 'value2',
);

$creditCard = array(
    "no3DSecure" => false,
    "no3DSecure" => false,
    "no3DSecureForStoredCard" => false,
    "rejectCardNot3DSecureEnrolled" => false,
    "rejectCreditCards" => false,
    "rejectDebitCards" => false,
    "rejectConsumerCards" => false,
    "rejectCorporateCards" => false,
    "rejectAuthenticationStatusA" => false,
    "rejectAuthenticationStatusU" => false,
);

$payment = array(
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

$payload = array(
    'payment' => $payment,
    'creditCard' => $creditCard,
);

try {
    $response = $request->curlRequest(
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/creditcard/payments",
        json_encode($payload)
    );

    if ($response['statusCode'] == 201) {
        $operationsArray = $response['response']->{'operations'};
        $index = array_search('view-authorization', array_column($operationsArray, 'rel'));

        if ($index == true) {
            $href = $operationsArray[$index]->{'href'};
            $dataout = array("creditcardhref" => $href);
            echo $twig->render('creditcard.html', $dataout);
        }
    }
} catch (Exception $e) {
    // Exception handling
}

?>
</body>

</html>
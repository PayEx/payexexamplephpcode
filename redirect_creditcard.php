<!DOCTYPE html>
<html>

<head>
    <title>creditcard redirect</title>
</head>

<body>
    <?php

require_once 'resources/Curl.php';
use \resources\Curl;

$request = new Curl();
$settingsData = require_once 'resources/settings.php';

$urls = [
    "completeUrl" => "https://example.com/payment-completed",
    "cancelUrl" => "https://example.com/payment-canceled",
    "callbackUrl" => "https://payexexamplephpcode.000webhostapp.com/resources/script_callback.php",
    "termsOfServiceUrl" => "https://payexexamplephpcode.000webhostapp.com/termsandconditions.pdf",
    "logoUrl" => "https://payexexamplephpcode.000webhostapp.com//logo.png",
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

$payment = [
    'operation' => 'Purchase',
    'intent' => "Authorization",
    'currency' => "SEK",
    'prices' => [
        [
        "type" => "creditcard",
        "amount" => 2500,
        "vatAmount" => 0,
        ]
    ],
    'description' => "Test Purchase",
    'userAgent' => "Mozilla/5.0",
    'language' => "nb-NO",
    'generatePaymentToken' => "false",
    'language' => "en-US",
    'urls' => $urls,
    'payeeInfo' => $payeeInfo,
    //'metadata' => $metadata,
];

$payload = [
    'payment' => $payment,
    'creditCard' => $creditCard,
];

try {
    $response = $request->curlRequest(
        $settingsData['AuthorizationBearer'],
        "POST",
        $settingsData['baseuri'] . "/psp/creditcard/payments",
        json_encode($payload)
    );

    if ($response['statusCode'] == 201) {
        $operationsArray = $response['response']->{'operations'};
        $index = array_search('redirect-authorization', array_column($operationsArray, 'rel'));

        if (isset($index)) {
            $redirecturl = $operationsArray[$index]->{'href'};

            // alternative 1
            // header("Location: " . $redirecturl);

            // alternative 2
            $redirectJS = "";
            $redirectJS .= '<script language="javascript">';
            $redirectJS .= 'window.location.href = "'.$redirecturl.'"';
            $redirectJS .= '</script>';
            echo $redirectJS;
            
            exit();
        }
    }
} catch (Exception $e) {
    // Exception handling
}

?>
</body>
</html>
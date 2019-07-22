<!DOCTYPE html>
<html>

<head>
    <title>creditcard redirect</title>
    <script src="jquery.min.js"></script>
</head>

<body>
    <?php

require_once 'resources/Curl.php';
use \resources\Curl;

$request = new Curl();
$settingsdata = require_once 'resources/settings.php';

$hostUrl = "https://payexexamplephpcode.000webhostapp.com";

$urls = [
    "completeUrl" => "https://example.com/payment-completed",
    "cancelUrl" => "https://example.com/payment-canceled",
    "callbackUrl" => $hostUrl."/resources/script_callback.php",
    "termsOfServiceUrl" => $hostUrl."/termsandconditions.pdf",
    "logoUrl" => $hostUrl."/logo.png",
];

$payeeInfo = [
    "payeeId" => $settingsdata['payeeId'],
    "payeeReference" => date("Ymdhis") . rand(100, 1000),
    "orderReference" => "order-100",
    "payeeName" => "Merchant1",
    "productCategory" => "A100",
];

$prices = [
    "type" => "creditcard",
    "amount" => 2500,
    "vatAmount" => 0,
];

$metadata = [
    'key1' => 'value1',
    'key2' => 'value2',
];

$creditCard = [
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
    "mailOrderTelephoneOrder" => "false",
];

$payment = [
    'operation' => 'Purchase',
    'intent' => "Authorization",
    'currency' => "SEK",
    'prices' => [$prices],
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
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/creditcard/payments",
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
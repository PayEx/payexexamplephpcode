
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

$metadata = array(
    'key1' => 'value1',
    'key2' => 'value2',
);

$creditCard = array(
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

$invoice = array(
    "feeAmount" => 1000,
    "invoiceType" => "PayExFinancingSe",
);

$swish = array(
    "enableEcomOnly" => false,
);

$items = array(array('creditCard' => $creditCard), array('invoice' => $invoice), array('swish' => $swish));

$paymentorder = array(
    'operation' => 'Purchase',
    'intent' => "Authorization",
    'currency' => "SEK",
    'amount' => 25000,
    'vatAmount' => 0,
    'description' => "Test Purchase",
    'userAgent' => "Mozilla/5.0",
    'language' => "nb-NO",
    'generatePaymentToken' => "false",
    'disablePaymentMenu' => "false",
    'urls' => $urls,
    'payeeInfo' => $payeeInfo,
    //'metadata' => $metadata,
    'items' => $items,
);

$payload = array(
    'paymentorder' => $paymentorder,
);

try {
    $response = $request->curlRequest(
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/paymentorders",
        json_encode($payload)
    );

    if ($response['statusCode'] == 201) {
        $operationsArray = $response['response']->{'operations'};
        $rel = 'view-paymentorder';
        $index = array_search($rel, array_column($operationsArray, 'rel'));
        $href = $operationsArray[$index]->{'href'};
        
        if ($index == true) {
            $dataout = array("paymentmenu" => $href);
            echo $twig->render('paymentmenu.html', $dataout);
        }
    }
} catch (Exception $e) {
    // Exception handling
}

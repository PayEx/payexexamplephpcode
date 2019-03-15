<?php

require __DIR__ . '/vendor/autoload.php';

include 'resources/Curl.php';
$request = new Curl();
$settingsdata = include 'resources/settings.php';

// Twig extension
$loader = new \Twig\Loader\FilesystemLoader('templates/');
$twig = new \Twig\Environment($loader);

// type of flow - initiate-consumer-session

if (isset($_GET["consumerProfileRef"]) == false) {
    $payloadConsumer = array(
        "operation" => "initiate-consumer-session",
        "msisdn" => "+4798765432",
        "email" => "olivia.nyhuus@example.com",
        "consumerCountryCode" => "NO",
        "nationalIdentifier" => array('socialSecurityNumber' => '26026708248', 'countryCode' => 'NO'),
    );

    $responseConsumer = $request->curlRequest(
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/consumers",
        json_encode($payloadConsumer)
    );

    if ($responseConsumer['statusCode'] == 200) {
        $operationsArray = $responseConsumer['response']->{'operations'};
        $rel = 'view-consumer-identification';
        $index = array_search($rel, array_column($operationsArray, 'rel'));

        if ($index == true) {
            $href = $operationsArray[$index]->{'href'};
            $dataout = array("consumer" => $href);
            echo $twig->render('checkout.html', $dataout);
        }
    }
}

// HTTP GET call(jQuery) from onConsumerIdentifiedEvent - see checkout.html in templates
if (isset($_GET["consumerProfileRef"]) == true) {
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

    $consumerProfileRef = filter_input(INPUT_GET, 'consumerProfileRef', FILTER_SANITIZE_STRING);

    $payer = array(
        'consumerProfileRef' => $consumerProfileRef,
    );

    $paymentorder = array(
        'operation' => 'Purchase',
        'intent' => "Authorization",
        'currency' => "SEK",
        'amount' => 25000,
        'vatAmount' => 0,
        'description' => "Test Purchase",
        'userAgent' => "Mozilla/5.0",
        'language' => "en-US",
        'generatePaymentToken' => "false",
        'disablePaymentMenu' => "false",
        'urls' => $urls,
        'payeeInfo' => $payeeInfo,
        'payer' => $payer,
        //'metadata' => $metadata,
        'items' => $items,
    );

    $payloadPaymentmenu = array(
        'paymentorder' => $paymentorder,
    );

    $responsePaymentmenu = $request->curlRequest(
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/paymentorders",
        json_encode($payloadPaymentmenu)
    );

    if ($responsePaymentmenu['statusCode'] == 201) {
        $operationsArray = $responsePaymentmenu['response']->{'operations'};
        $rel = 'view-paymentorder';
        $index = array_search($rel, array_column($operationsArray, 'rel'));

        if ($index == true) {
            $href = $operationsArray[$index]->{'href'};
            echo '<p class="paymentmenu-token">' . $href . '</p>';
        }
    }
}

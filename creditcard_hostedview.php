<!DOCTYPE html>
<html>

<head>
    <title>creditcard redirect</title>
    <script src="jquery.min.js"></script>
</head>

<body>
    <div id="creditcard"></div>
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

    if ($response['statuscode'] == 201) {

        $operationsArray = $response['response']->{'operations'};
        $index = array_search('view-authorization', array_column($operationsArray, 'rel'));

        if ($index == true) {
            $href = $operationsArray[$index]->{'href'};
            $hrefcorrect = str_replace('&operation=authorize', '', $href);

            echo '
		    <script type="text/javascript" src="' . $hrefcorrect . '"></script>
            <script language="javascript">
				"use strict";
				let config = {
                    container: "creditcard",
                    OnPaymentCompleted: function(OnPaymentCompletedEvent) {
                        console.log(OnPaymentCompletedEvent);
                    },
                    OnPaymentFailed: function(OnPaymentFailedEvent) {
                        console.log(OnPaymentFailedEvent);
                    },
                    OnPaymentToS: function(OnPaymentToSEvent) {
                        console.log(OnPaymentToSEvent);
                    },
                    ApplicationConfigured: function(ApplicationConfiguredEvent) {
                        console.log(ApplicationConfiguredEvent);
                    },
                    ApplicationConfiguredToClient: function(ApplicationConfiguredToClientEvent) {
                        console.log(ApplicationConfiguredToClientEvent);
                    },
                    PaymentAbort: function(PaymentAbortEvent) {
                        console.log(PaymentAbortEvent);
                    },
                    PaymentRefresh: function(PaymentRefreshEvent) {
                        console.log(PaymentRefreshEvent);
                    },
                    onError: function(error) {
                        console.error(error);
                    },
                };
					payex.hostedView.creditCard(config).open();
					//payex.hostedView.creditCard().refresh();
					//payex.hostedView.creditCard().close();
        </script>
        ';
        }
    }

} catch (Exception $e) {
    // Exception handling
}

?>
</body>

</html>
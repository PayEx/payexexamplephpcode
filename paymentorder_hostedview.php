<!DOCTYPE html>
<html>

<head>
    <title>paymentorder</title>
    <script src="jquery.min.js"></script>
</head>

<body>
    <div id="paymentMenu"></div>

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

$invoice = array
(
"feeAmount" => 1000,
"invoiceType" => "PayExFinancingSe",
);

$swish = array
(
"enableEcomOnly" => false,
);

$items = array(array('creditCard' => $creditCard), array('invoice' => $invoice), array('swish' => $swish));

$paymentorder = array
    (
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

$payload = array
    (
    'paymentorder' => $paymentorder,
);

try {

    $response = $request->payex_request(
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/paymentorders",
        json_encode($payload)
    );

    $operationsArray = $response->{'operations'};
    $rel = 'view-paymentorder';
    $index = array_search($rel, array_column($operationsArray, 'rel'));

    if ($index == true) {
        $href = $operationsArray[$index]->{'href'};

        echo '
		<script type="text/javascript" src="' . $href . '"></script>
        <script language="javascript">
				"use strict";
				let config = {
                container: "paymentMenu",
					onPaymentCompleted: function(paymentCompletedEvent) {
                        // event handling
                        // please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/payment-orders-resource/#HPaymentMenuEvents
                        // please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/payex-checkout-main/payex-checkout-anonymous-payments/
						alert("purchase completed");
					},
					onPaymentFailed: function(paymentFailedEvent) {
						//console.log(paymentFailedEvent);
					},
					onPaymentCreated: function(paymentCreatedEvent) {
						//console.log(paymentCreatedEvent);
					},
					onPaymentToS: function(paymentToSEvent) {
						//console.log(paymentToSEvent);
					},
					onPaymentMenuInstrumentSelected: function(paymentMenuInstrumentSelectedEvent) {
						//console.log(paymentMenuInstrumentSelectedEvent);
					},
					onError: function(error) {
						//console.error(error);
					},
          };
					payex.hostedView.paymentMenu(config).open();
					//payex.hostedView.paymentMenu().refresh();
					//payex.hostedView.paymentMenu().close();
        </script>
        ';
    }
} catch (Exception $e) {
    // Exception handling
}
?>

</body>

</html>
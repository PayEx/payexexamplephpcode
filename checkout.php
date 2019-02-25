<!DOCTYPE html>
<html>

<head>
    <script src="jquery.min.js"></script>
    <title>checkout</title>
</head>

<body>
    <div id="checkin"></div>
    <div id="paymentMenu"></div>

    <?php

include 'resources/payexapi.php';
$request = new payexapi();
$settingsdata = include 'resources/settings.php';

if (isset($_GET["consumerProfileRef"]) == false) {

    $payloadConsumer = array
        (
        "operation" => "initiate-consumer-session",
        "msisdn" => "+4798765432",
        "email" => "olivia.nyhuus@example.com",
        "consumerCountryCode" => "NO",
        "nationalIdentifier" => array('socialSecurityNumber' => '26026708248', 'countryCode' => 'NO'),
    );

    $responseConsumer = $request->payex_request(
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/consumers",
        json_encode($payloadConsumer)
    );

    $operationsArray = $responseConsumer->{'operations'};
    $rel = 'view-consumer-identification';
    $index = array_search($rel, array_column($operationsArray, 'rel'));

    // please read about styling here => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/styling/
    $style = array(
        'body' => array('backgroundColor' => "#555", 'color' => "#bbb"),
        'button' => array('backgroundColor' => "#36ac4c", 'color' => "#fff"),
        'secondaryButton' => array('backgroundColor' => "#555", 'border' => "solid 1px #bbb"),
        'formGroup' => array('color' => "#bbb", 'backgroundColor' => "#555"),
        'label' => array('color' => "#bbb"),
    );

    if ($index == true) {
        $href = $operationsArray[$index]->{'href'};

        echo '<script src="' . $href . '"></script>';
        echo '<script language="javascript">"use strict";';
        //echo 'let stylevariable = ' . json_encode($style);
        echo '
	    let configconsumer = {
		//style : stylevariable,
		container: "checkin",
		onConsumerIdentified: function(onConsumerIdentifiedEvent) {
		// event handling onConsumerIdentified
		// please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/consumers-resource/
		// HTTP GET call(jQuery)
		let phpfiletocall = "checkout.php";
		$.get(phpfiletocall+"?consumerProfileRef="+onConsumerIdentifiedEvent.consumerProfileRef, function(data) {
		let parsedHtmlArr = $.parseHTML(data);
		let paymentmenutokenobject = parsedHtmlArr.find( x => x.className == "paymentmenu-token");
		let srcUrl = paymentmenutokenobject.innerText.trim();
		let script = document.createElement("script");
		script.type = "text/javascript";
		script.async = true;
		script.src = srcUrl;
		script.onload = function()
			{
			let script2 = document.createElement("script");
			script2.setAttribute("language","javascript");
			script2.async = false;
			let node = document.createTextNode(`
			let configpaymentMenu = {
				//style : stylevariable,
				container: "paymentMenu",
				onPaymentCompleted: function(paymentCompletedEvent) {

					// event handling onPaymentCompleted
					// please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/payment-orders-resource/
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
			}
			payex.hostedView.paymentMenu(configpaymentMenu).open();`);
			script2.appendChild(node);
			document.body.appendChild(script2);
			}
			document.head.appendChild(script);
			});
		},

		onShippingDetailsAvailable : function(onShippingDetailsAvailableEvent ) {
			//console.log(onShippingDetailsAvailableEvent);
		},
		onError: function(error) {
			//console.error(error);
		}
		};

        payex.hostedView.consumer(configconsumer).open();
        ';
        echo '</script>';
    }
}

// HTTP GET call(jQuery) from onConsumerIdentifiedEvent
if (isset($_GET["consumerProfileRef"]) == true) {

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

    $payloadPaymentmenu = array
        (
        'paymentorder' => $paymentorder,
    );

    $responsePaymentmenu = $request->payex_request(
        $settingsdata['AuthorizationBearer'],
        "POST",
        $settingsdata['baseuri'] . "/psp/paymentorders",
        json_encode($payloadPaymentmenu)
    );

    $operationsArray = $responsePaymentmenu->{'operations'};
    $rel = 'view-paymentorder';
    $index = array_search($rel, array_column($operationsArray, 'rel'));

    if ($index == true) {
        $href = $operationsArray[$index]->{'href'};

        echo '<p class="paymentmenu-token">' . $href . '</p>';
    }
}

?>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>checkout</title>
    <script src="js/jquery.min.js"></script>
    <link rel="stylesheet" href="css/bulma.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
</head>

<body>
    <section class="section">
        <div class="container">
            <div class="tile is-ancestor">
                <div id="checkin" class="tile is-3 is-vertical is-parent box">
                    <p class="title">Consumer</p>
                </div>
                <div id="paymentMenu" class="tile is-child box">
                    <p class="title">Paymentmenu</p>
                </div>
            </div>
    </section>
</body>

<script src="<?php echo $href ?>"></script>
<script language="javascript">
"use strict";

let stylevariable = `
    {
        "style": {
            "body": {
                "backgroundColor": "#555",
                "color": "#bbb"
            },
            "button": {
                "backgroundColor": "#36ac4c",
                "color": "#fff"
            },
            "secondaryButton": {
                "backgroundColor": "#555",
                "border": "solid 1px #bbb"
            },
            "formGroup": {
                "color": "#bbb",
                "backgroundColor": "#555"
            },
            "label": {
                "color": "#bbb"
            }
        }    
    }`;

let configconsumer = {
    // uncomment to add styling
    //style : stylevariable,
    container: "checkin",
    onConsumerIdentified: function(onConsumerIdentifiedEvent) {
        // event handling onConsumerIdentified
        // please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/consumers-resource/
        // HTTP GET call(jQuery)
        let phpfiletocall = "hostedview_checkout_consumerProfileRef.php";
        $.get(phpfiletocall + "?consumerProfileRef=" + onConsumerIdentifiedEvent.consumerProfileRef, function(
            data) {
            let parsedHtmlArr = $.parseHTML(data);
            let paymentmenutokenobject = parsedHtmlArr.find(x => x.className == "paymentmenu-token");
            let srcUrl = paymentmenutokenobject.innerText.trim();
            let script = document.createElement("script");
            script.type = "text/javascript";
            script.async = true;
            script.src = srcUrl;
            script.onload = function() {
                let script2 = document.createElement("script");
                script2.setAttribute("language", "javascript");
                script2.async = false;
                let node = document.createTextNode(`
                    let configpaymentMenu = {
                        // uncomment to add styling
                        // style : stylevariable,
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

    onShippingDetailsAvailable: function(onShippingDetailsAvailableEvent) {
        //console.log(onShippingDetailsAvailableEvent);
    },
    OnBillingDetailsAvailable: function(OnBillingDetailsAvailableEvent) {
        //console.log(OnBillingDetailsAvailableEvent);
    },
    onError: function(error) {
        //console.error(error);
    }
};

payex.hostedView.consumer(configconsumer).open();
</script>

<footer class="footer">
    <div class="content has-text-centered">
        <p>
            <strong>checkout in PayEx</strong>
        </p>
    </div>
</footer>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>hosted view</title>
</head>

<body>
    <div id="paymentmenu"></div>
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

let config = {
    container: "paymentmenu",
    onPaymentCompleted: function(paymentCompletedEvent) {
        // event handling
        // please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/payment-orders-resource/#HPaymentMenuEvents
        // please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/payex-checkout-main/payex-checkout-anonymous-payments/
        alert("purchase completed");
    },
    onPaymentFailed: function(paymentFailedEvent) {
        console.log(paymentFailedEvent);
    },
    onPaymentCreated: function(paymentCreatedEvent) {
        console.log(paymentCreatedEvent);
    },
    onPaymentToS: function(paymentToSEvent) {
        console.log(paymentToSEvent);
    },
    onPaymentMenuInstrumentSelected: function(paymentMenuInstrumentSelectedEvent) {
        console.log(paymentMenuInstrumentSelectedEvent);
    },
    onError: function(error) {
        console.error(error);
    },
};
payex.hostedView.paymentMenu(config).open();
//payex.hostedView.paymentMenu().refresh();
//payex.hostedView.paymentMenu().close();
</script>

</html>
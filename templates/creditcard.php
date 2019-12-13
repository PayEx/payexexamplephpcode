<!DOCTYPE html>
<html lang="en">

<head>
    <title>hosted view</title>
</head>

<body>
    <div id="creditcard"></div>
</body>

<script src="<?php echo $href ?>"></script>
<script language="javascript">
"use strict";

let stylecreditcard = {
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
    };

let config = {
    container: "creditcard",
    //style: stylecreditcard,
    OnPaymentCompleted: function(OnPaymentCompletedEvent) {
        alert("purchase completed");
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
</script>

</html>
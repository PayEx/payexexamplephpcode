"use strict";

let stylecontainer = {
    "style": {
        "body": {
            "backgroundColor": "black",
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

let configpaymentorder = {
    container: "paymentMenu",
    culture: 'en-US',
    //style: stylecontainer,
    onPaymentCompleted: function(paymentCompletedEvent) {
        // event handling
        // please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference/payment-orders-resource/#HPaymentMenuEvents
        // please read: https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/payex-checkout-main/payex-checkout-anonymous-payments/
        alert("purchase completed");
        console.log(paymentCompletedEvent);
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
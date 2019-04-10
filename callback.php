<?php

include 'resources/Curl.php';
$request = new Curl();
$settingsdata = include 'resources/settings.php';

// please read about callback handling => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference

$raw_post_data = file_get_contents('php://input');

if ($raw_post_data == true) {
    
    // logg
    if ($settingsdata['logging'] == true) {
        $folder = 'logs';
        if (!is_dir($folder)) {
            mkdir($folder, 0777);
        }
        $newlog = PHP_EOL . date("Y-m-d h:i:s") . PHP_EOL;
        $newlog .= $raw_post_data;
        file_put_contents($folder . '/Callback_' . date("Ymd") . '.txt', $newlog, FILE_APPEND);
    }

    // API request
    try {
        $response = $request->curlRequest(
            $settingsdata['AuthorizationBearer'],
            "GET",
            $settingsdata['baseuri'] . json_decode($raw_post_data)->{'payment'}->{'id'}, //payment
            //$settingsdata['baseuri'] . json_decode($raw_post_data)->{'paymentOrder'}->{'id'}, // paymentOrder
            '' // payload content not needed, but empty string must be present because of the method parameter
        );
        if ($response['statusCode'] == 500) {
            // in case we receive an internal error from PayEx, we want PayEx to send a callback later
            http_response_code(500);
        } else {
            // respond back http 200 OK to PayEx(callback server)
            http_response_code(200);
            // do something with the $response['response'] data
        }
    } catch (Exception $e) {
        // Exception handling
        http_response_code(500);
    }
} else {
    // in case file_get_contents evaluates to false, we want PayEx to send callback later
    http_response_code(500);
}
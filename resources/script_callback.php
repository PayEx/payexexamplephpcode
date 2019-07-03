<?php

namespace resources;

require_once 'Curl.php';
require_once 'Logger.php';
$request = new \resources\Curl();
$logger = new \resources\Logger();
$settingsData = require_once 'settings.php';

// please read about callback handling => https://developer.payex.com/xwiki/wiki/developer/view/Main/ecommerce/technical-reference

//ini_set("allow_url_fopen", true);

$allowed_servers = [
    'payexserver' => '82.115.146.1',
    'localhost' => '127.0.0.1'
];

if (in_array($_SERVER['REMOTE_ADDR'], $allowed_servers) == false) {
    exit();
}

try {
    $raw_post_data = file_get_contents('php://input');
    // validate that payment->id exist
    if (isset(json_decode($raw_post_data)->{'payment'}->{'id'})) {
        http_response_code(200);
        
        $response = $request->curlRequest(
            $settingsData['AuthorizationBearer'],
            "GET",
            $settingsData['baseuri'] . json_decode($raw_post_data)->{'payment'}->{'id'}, //payment
        //$settingsData['baseuri'] . json_decode($raw_post_data)->{'paymentOrder'}->{'id'}, // paymentOrder
        '' // payload content not needed, but empty string must be present because of the method parameter
        );
        // do something with the $response['response'] data
    } else {
        http_response_code(500);
    }

    if ($settingsData['logging'] == true) {
        $logdata = '';
        $logdata .= 'Remote IP: '.$_SERVER['REMOTE_ADDR'] . PHP_EOL;
        $logdata .= 'Host: '.$_SERVER['HTTP_HOST'] . PHP_EOL;
        $logdata .= 'Request time: '.$_SERVER['REQUEST_TIME'] . PHP_EOL;
        $logdata .= 'Request method: '.$_SERVER['REQUEST_METHOD'] . PHP_EOL;
        $logdata .= 'Body content: '.$raw_post_data . PHP_EOL;
        $logger->createLog('CALLBACK', $logdata, 'INFO');
    }
} catch (Exception $e) {
    // Exception handling
    http_response_code(500);
}
